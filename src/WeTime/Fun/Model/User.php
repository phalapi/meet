<?php
/**
 * 用户数据模型类
 */

class Model_User extends PhalApi_Model_NotORM {

    public function getUserList($allUids) {
        $rows = $this->getORM()
            ->select('id, avatar')
            ->where('id', $allUids)
            ->fetchAll();

        $list = array();
        foreach ($rows as $row) {
            $list[$row['id']] = $row;
        }

        return $list;
    }
}

