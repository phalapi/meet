<?php
/**
 * 关注数据模型类
 */

class Model_Follow extends PhalApi_Model_NotORM {

    public function getFollowUids($userId) {
        $rows = $this->getORM()
            ->select('touid')
            ->where('uid', $userId)
            ->fetchAll();

        $uids = array();
        foreach ($rows as $row) {
            $uids[] = intval($row['touid']);
        }

        return $uids;
    }
}
