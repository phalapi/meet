<?php

class Model_Group extends PhalApi_Model_NotORM {
    public function getGroupInfoByGroupId($groupId) {
        return $this->getORM()
            ->select('number, groupname, password')
            ->where('id', $groupId)
            ->fetchRow();

        $key = 'group_info_' . $groupId;
        $data = DI()->cache->get($key);
        if (!empty($data)) {
            return $data;
        }

        $data = $this->getORM()
            ->select('number, groupname, password')
            ->where('id', $groupId)
            ->fetchRow();
        DI()->cache->set($key, $data, 600);
        return $data;
    }
}
