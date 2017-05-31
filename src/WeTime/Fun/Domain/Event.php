<?php
/**
 * 日历事件领域业务类
 */

class Domain_Event {

    // 0：已删除；1：未完成；2：已完成
    const STATE_DELETED = '0';
    const STATE_ACTIVE  = '1';
    const STATE_DONE    = '2';

    protected static $uidsCache = array();

    public function post($newEvent) {
        $newEvent['state'] = self::STATE_ACTIVE;
        if (empty($newEvent['createtime'])) {
            $newEvent['createtime'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        }

        $model = new Model_Event();
        $id = $model->insert($newEvent);

        return $id;
    }

    public function getSpaceTotal($userId, $createTime) {
        $allUids = $this->getAllUidsForEvent($userId);

        $model = new Model_Event();
        return $model->getSpaceTotal($allUids, $createTime);
    }

    public function getSpaceList($userId, $createTime, $perpage = 20, $page = 1) {
        $allUids = $this->getAllUidsForEvent($userId);

        $model = new Model_Event();
        $list = $model->getSpaceList($allUids, $createTime, $perpage, $page);

        $domainUser = new Domain_User();
        $userList = $domainUser->getUserList($allUids);
        foreach ($list as &$eventRef) {
            $eventRef['user'] = array(
                'avatar' => $userList[$eventRef['uid']]['avatar'],
            );
        }

        return $list;
    }

    protected function getAllUidsForEvent($userId) {
        if (!isset(self::$uidsCache[$userId])) {
            $domainFollow = new Domain_Follow();
            $followUids = $domainFollow->getFollowUids($userId);
            self::$uidsCache[$userId] = array_merge($followUids, array($userId));
        }

        return self::$uidsCache[$userId];
    }

    public function operate($userId, $id, $state) {
        $model = new Model_Event();
        return $model->operate($userId, $id, $state);
    }
}
