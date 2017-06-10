<?php
/**
 * 动态评论
 *
 * @author dogstar 20150425
 */

class Domain_Feed_Comment {

    /**
     * 用户$userId针对动态$feedId发布$message评论
     */
    public function uploadComment($userId, $feedId, $message) {
        if ($userId <= 0 || $feedId <= 0 || $message === '') {
            return 0;
        }

        $newData = array(
            'feed_id' => $feedId,
            'user_id' => $userId,
            'message' => $message,
            'dateline' => $_SERVER['REQUEST_TIME'],
        );

        //取动态作者ID作为被评论的用户ID
        $domain = new Domain_Feed();
        $newData['to_user_id'] = $domain->getUserId($feedId);

        $commentId = $this->commonComment($newData);

        //消息通知，如果此操作过于耗时，可以改用后台异步计划任务 @dogstar
        if ($commentId > 0 && $newData['to_user_id'] != $userId) {
            $domain = new Domain_Notice();
            $domain->notifyForSomeCommentMyFeed($feedId, $commentId);
        }

        return $commentId;
    }

    /**
     * 用户$userId针对动态$feedId的评论$toCommentId，回复了$message
     *
     * @param int $toUserId 被评论的用户，为避过多关联查询，这里作数据冗余
     */
    public function replyComment($userId, $feedId, $message, $toCommentId, $toUserId) {
        if ($userId <= 0 || $feedId <= 0 || $message === '' || $toCommentId <= 0) {
            return 0;
        }

        $newData = array(
            'feed_id' => $feedId,
            'user_id' => $userId,
            'message' => $message,
            'to_user_id' => $toUserId,
            'to_comment_id' => $toCommentId,
            'dateline' => $_SERVER['REQUEST_TIME'],
        );

        return $this->commonComment($newData);
    }

    /**
     * 分表存储 + 刷新评论数量
     */
    protected function commonComment($newData) {
        $model = new Model_FeedComments();
        $id = $model->insert($newData, $newData['feed_id']);

        //刷新评论数量
        $domain = new Domain_Feed();
        $domain->refreshCommentNum($newData['feed_id']);

        return intval($id);
    }

    public function getCommentInfo($feedId, $commentId) {
        if ($feedId <= 0 || $commentId <= 0) {
            return array();
        }

        $model = new Model_FeedComments();
        return $model->getCommentInfo($feedId, $commentId);
    }

    public function deleteComment($userId, $feedId, $commentId) {
        if ($feedId <= 0 || $commentId <= 0) {
            return;
        }

        $model = new Model_FeedComments();
        $model->deleteComment($userId, $feedId, $commentId);
        
        //刷新评论数量
        $domain = new Domain_Feed();
        $domain->refreshCommentNum($feedId);
    }

    /**
     * 复杂的列表获取 
     */
    public function getCommentList($userId, $feedId, $page, $perpage) {
        $rs = array();

        if ($feedId <= 0) {
            return $rs;
        }

        //先取基本的评论信息
        $model = new Model_FeedComments();
        $list = $model->getCommentList($feedId, $page, $perpage);

        $userIds = array();
        foreach ($list as $item) {
            $userIds[] = $item['user_id'];
            $userIds[] = $item['to_user_id'];
        }

        //批量获取全部用户的信息
        $domainUserInfo = new Domain_User_Info();
        $userInfos = $domainUserInfo->freeGetMultiUserInfo($userIds, 'nickname, avatar, UUID');

        //批量获取别名
        $domainUserRelation = new Domain_User_Relation();
        $userAliass = $domainUserRelation->getMultiAlias($userId, $userIds);

        //神奇的列表组合
        foreach ($list as $item) {
            $comment = array();

            $comment['comment_id']  = intval($item['id']);
            $comment['message']     = $item['message'];
            $comment['dateline']    = intval($item['dateline']);
            $comment['to_comment_id'] = intval($item['to_comment_id']);

            //评论的用户信息
            $uid = $item['user_id'];
            $comment['UUID']        = isset($userInfos[$uid]) ? $userInfos[$uid]['UUID'] : '';
            $comment['nickname']    = isset($userInfos[$uid]) ? $userInfos[$uid]['nickname'] : '';
            $comment['avatar']      = isset($userInfos[$uid]) ? $userInfos[$uid]['avatar'] : '';

            $comment['alias']       = isset($userAliass[$uid])? $userAliass[$uid] : '';

            //被评论用户的信息
            $tuid = $item['to_user_id'];
            $comment['to_UUID']     = isset($userInfos[$tuid]) ? $userInfos[$tuid]['UUID'] : '';
            $comment['to_nickname'] = isset($userInfos[$tuid]) ? $userInfos[$tuid]['nickname'] : '';
            $comment['to_avatar']   = isset($userInfos[$tuid]) ? $userInfos[$tuid]['avatar'] : '';

            $comment['to_alias']    = isset($userAliass[$tuid]) ? $userAliass[$tuid] : '';

            $rs[] = $comment;
        }

        return $rs;
    }

    public function getCommentTotalNum($feedId) {
        if ($feedId <= 0) {
            return 0;
        }

        $domain = new Domain_Feed();
        return $domain->getCommentsNum($feedId);

        $model = new Model_FeedComments();
        return $model->getCommentTotalNum($feedId);
    }

    public function isCommentExist($userId, $feedId, $commentId) {
        if ($feedId <= 0) {
            return false;
        }

        $model = new Model_FeedComments();
        return $model->isCommentExist($userId, $feedId, $commentId);
    }
}
