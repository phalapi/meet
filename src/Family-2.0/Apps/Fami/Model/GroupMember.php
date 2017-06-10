<?php

class Model_GroupMember extends PhalApi_Model_NotORM {

    protected function getTableName($id = null) {
        return 'group_member';
    }

    public function hasJoined($userId, $groupId, $detectDeviceType = 'all', $deleteState = Domain_Group_Helper::GROUP_NOT_DELETE) {
        $num = $this->getORM()
            ->where('user_id', $userId)
            ->where('group_id', $groupId)
            ->count('id');

        return $num > 0 ? true : false;
    }

    public function getGroupMemberAmount($groupId) {
        return $this->getORM()
            ->where('group_id', $groupId)
            ->count('id');
    }

    public function getMultiGroupMemberAmount($groupIds) {

        if (!is_array($groupIds) || empty($groupIds)) {
            return array();
        }

        // 批量获取
        $sql = 'SELECT COUNT( CASE WHEN group_id = ' . $groupIds[0] . ' THEN 1 ELSE NULL END ) AS amount' . $groupIds[0];
        foreach ($groupIds as $key => $aGroupId) {
            if ($key == 0) {
                continue;
            }
            $sql .= (', COUNT( CASE WHEN group_id = ' . $aGroupId . ' THEN 1 ELSE NULL END ) AS amount' . $aGroupId);
        }
        $sql .= ' FROM fami_group_member';

        $params = array();
        $rows = $this->getORM()->queryRows($sql, $params);

        if (empty($rows)) {
            return array();
        }

        return $rows[0];
    }

    /**
     * 根据传入的家庭圈id数组，获取所有家庭圈的全部成员
     * @param groupIds  待取的家庭圈id数组
     */
    public function  getMultiAllMembers($groupIds) {
        return $this->getORM()
            ->where('group_id', $groupIds)
            ->fetchAll();
    }

    public function clearAllMembers($groupId, $exceptUserId = 0) {
        if ($exceptUserId > 0) {
            $this->getORM()
                ->where('group_id', $groupId)
                ->where('user_id <> ?', $exceptUserId)
                ->delete();
            } else {
                $this->getORM()
                    ->where('group_id', $groupId)
                    ->delete();
            }
    }

    /**
     * 根据传入的家庭圈id，分页获取该家庭圈的成员
     * @param groupId   待取的家庭圈id
     * @param page      分页数
     * @param perpage   每页数量
     */
    public function getMemberList($groupId, $page, $perpage, $detectDeviceType = 'all') {

        $sql = 'SELECT gm.* FROM fami_group_member AS gm '
            . 'LEFT JOIN fami_group AS g ON gm.group_id = g.id '
            . 'WHERE gm.group_id = :group_id AND g.type IN (:type) AND g.is_delete IN (:isDelete) '
            // . 'ORDER BY gm.id DESC '
            . 'LIMIT :startPos, :perpage';

        $params = array(
            ':group_id' => intval($groupId), 
            ':type' => Domain_Group_Helper::groupType($detectDeviceType, false),
            ':isDelete' => Domain_Group_Helper::groupDeleteState(Domain_Group_Helper::GROUP_NOT_DELETE, false),
            ':startPos' => intval(($page - 1) * $perpage), 
            ':perpage' => intval($perpage),
        );

        $sql = strtr($sql, $params);

        $rows = $this->getORM()->queryRows($sql, $params);
        
        return $rows;
    }

    public function quitGroup($userId, $groupId) {
        return $this->getORM()
            ->where('user_id', $userId)
            ->where('group_id', $groupId)
            ->delete();
    }

    /**
     * 批量退出家庭群（创建者不能离开）
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
    public function quitMultiGroups($userId, $groupIds) {
        
        if ($userId <= 0 || empty($groupIds) || !is_array($groupIds)) {
            return false;
        }

        $this->getORM()
            ->where('user_id', $userId)
            ->where('group_id', $groupIds)
            ->delete();
    }

    /**
     * 批量用户退出批量家庭群（创建者不能离开）
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
    public function quitMultiUserAndMultiGroups($userIds, $groupIds) {
        
        if (empty($userIds) || !is_array($userIds) || empty($groupIds) || !is_array($groupIds)) {
            return false;
        }

        $this->getORM()
            ->where('user_id', $userIds)
            ->where('group_id', $groupIds)
            ->delete();
    }

    public function getAllGroupIdsByUserId($userId, $detectDeviceType = 'all', $deleteState = Domain_Group_Helper::GROUP_NOT_DELETE) {

        $sql = 'SELECT gm.group_id FROM fami_group_member AS gm '
            . 'LEFT JOIN fami_group AS g ON gm.group_id = g.id '
            . 'WHERE gm.user_id = :userId AND g.type IN (:type) AND g.is_delete IN (:isDelete) '
            . 'ORDER BY gm.id DESC';

        $params = array(
            ':userId' => intval($userId), 
            ':type' => Domain_Group_Helper::groupType($detectDeviceType, false),
            ':isDelete' => Domain_Group_Helper::groupDeleteState($deleteState, false),
        );

        $sql = strtr($sql, $params);

        $rows = $this->getORM()->queryRows($sql, $params);
        

        // $rows = $this->getORM()
        //     ->select('group_id')
        //     ->where('user_id', $userId)
        //     ->order('id DESC')
        //     ->fetchAll();
        
        $rs = array();
        if (empty($rows)) {
            return $rs;
        }
        foreach ($rows as $row) {
            $rs[] = intval($row['group_id']);
        }
        return $rs;
    }

    /**
     * 取多个用户之间共同的组ID
     */
    public function getCommonGroups($userIds, $detectDeviceType = 'all', $deleteState = Domain_Group_Helper::GROUP_NOT_DELETE) {
        if (empty($userIds)) {
            return array();
        }

        $rs = array();

        // 如果 $userIds里的两个元素是一样的，则用此查询返回为空；要将group have两个查询条件去掉
        $sql = 'SELECT DISTINCT gm.group_id FROM fami_group_member AS gm '
            . 'LEFT JOIN fami_group AS g ON gm.group_id = g.id '
            . 'WHERE gm.user_id IN (:userIds) '
            . 'AND g.type IN (:type) AND g.is_delete IN (:isDelete) ';

        if ($userIds[0] != $userIds[1]) {
            $sql .= ('GROUP BY gm.group_id ' . 'HAVING COUNT(gm.user_id) > 1');
        }

        $params = array(
            ':userIds' => implode(',', $userIds),
            ':type' => Domain_Group_Helper::groupType($detectDeviceType, false),
            ':isDelete' => Domain_Group_Helper::groupDeleteState($deleteState, false),
        );

        $sql = strtr($sql, $params);
        
        $rows = $this->getORM()->queryAll($sql, $params);

        foreach ($rows as $row) {
            $rs[] = intval($row['group_id']);
        }
        
        return $rs;
    }

    /**
     *  获取用户所拥有的家庭群数量（包括创建和加入的）
     *  @param $userId 待查询的用户
     */
    public function getGroupsAmount($userId) {
        if ($userId <= 0) {
            return 0;
        }
        $num = $this->getORM()
            ->where('user_id', $userId)
            ->count();
        return intval($num);
    }
}
