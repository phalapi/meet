<?php
/**
 * 评论
 *
 * - 根据feed_id作分表
 *
 * @author dogstar 20150425
 */

class Model_FeedComments extends PhalApi_Model_NotORM {

    const TABLE_NUM = 100;

    protected function getTableName($id = null) {
        $tableName = 'feed_comments';
        if ($id !== null) {
            $tableName .= '_' . ($id % self::TABLE_NUM);
        }
        return $tableName;
    }

    public function getCommentsNum($feedId) {
        $num = $this->getORM($feedId)
            ->where('feed_id', $feedId)
            ->count('id');
        return intval($num);
    }

    public function getCommentInfo($feedId, $commentId) {
        return $this->getORM($feedId)
            ->select('*')
            ->where('id', $commentId)
            ->fetch();
    }

    public function deleteComment($userId, $feedId, $commentId) {
        return $this->getORM($feedId)
            ->where('user_id', $userId)
            ->where('feed_id', $feedId)
            ->where('id', $commentId)
            ->delete();
    }

    public function getCommentList($feedId, $page, $perpage) {
        $rows = $this->getORM($feedId)
            ->select('*')
            ->where('feed_id', $feedId)
            ->limit(($page - 1) * $perpage, $perpage)
            ->fetchAll();

        return !empty($rows) ? $rows : array();
    }

    public function getCommentTotalNum($feedId) {
        $num = $this->getORM($feedId)
            ->where('feed_id', $feedId)
            ->count('id');

        return intval($num);
    }

    public function isCommentExist($userId, $feedId, $commentId) {
        $num = $this->getORM($feedId)
            ->where('feed_id', $feedId)
            ->where('id', $commentId)
            ->where('user_id', $userId)
            ->count('id');
        return intval($num) > 0 ? true : false;
    }
}
