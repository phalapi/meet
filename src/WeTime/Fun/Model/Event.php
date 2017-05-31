<?php
/**
 * 日历事件数据模型类
 */

class Model_Event extends PhalApi_Model_NotORM {

    public function getSpaceTotal($allUids, $createTime) {
        $total = $this->getORM()
            ->where('uid', $allUids)
            ->where('createtime < ?', $createTime)
            ->where('tousers', '1')
            ->count('id');
        return intval($total);
    }

    public function getSpaceList($allUids, $createTime, $perpage, $page) {
        return $this->getORM()
            ->select('id, uid, title, content, createtime')
            ->where('uid', $allUids)
            ->where('createtime < ?', $createTime)
            ->where('tousers', '1')
            ->limit(($page - 1) * $perpage, $perpage)
            ->order('createtime DESC')
            ->fetchAll();
    }

    public function operate($userId, $id, $state) {
        return $this->getORM()
            ->where('uid', $userId)
            ->where('id', $id)
            ->update(array('state' => $state));
    }
}
