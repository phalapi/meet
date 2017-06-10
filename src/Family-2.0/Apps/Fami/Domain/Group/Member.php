<?php
/**
 * 家庭组成员业务类
 *
 * @author dogstar 20150402
 */

class Domain_Group_Member {

    /**
     * 加入家庭组
     *
     * - 自动刷新家庭人数
     */
    public function joinGroup($userId, $groupId) {
        if ($userId <= 0 || $groupId <= 0) {
            return false;
        }

        $newData = array();
        $newData['user_id'] = $userId;
        $newData['group_id'] = $groupId;

        $model = new Model_GroupMember();
        $id = $model->insert($newData);

        return $id > 0 ? true : false;
    }

    public function hasJoined($userId, $groupId, $detectDeviceType = 'all', $deleteState = Domain_Group_Helper::GROUP_NOT_DELETE) {
        $model = new Model_GroupMember();
        return $model->hasJoined($userId, $groupId, $detectDeviceType, $deleteState);
    }

    public function quitGroup($userId, $groupId) {
        if ($userId <= 0 || $groupId <= 0) {
            return false;
        }

        $model = new Model_GroupMember();
        $model->quitGroup($userId, $groupId);

        $domain = new Domain_Group();
        $domain->refreshMemberAmount($groupId);


        // 1、将该用户绑定了此家庭群的设备的数据一起删掉（device_user_group表）
        $domainDeviceUserGroup = new Domain_Device_UserGroup();
        $deviceInfos = $domainDeviceUserGroup->getDeviceInfosByGroupId($userId, $groupId, 'device_id'); // 先保存deviceIds（一个家庭群可能会被多个设备绑定）
        $deviceIds = array();
        foreach ($deviceInfos as $aDeviceInfo) {
            $deviceIds[] = intval($aDeviceInfo['device_id']);
        }
        
        $domainDeviceUserGroup->deleteDataByGroupId($groupId, $userId); // 解绑家庭群

        if (!empty($deviceIds)) {
            // 2、解绑设备
            $domainDeviceUser = new Domain_Device_User();
            $domainDeviceUser->unBindAllDeviceForUser($userId, $deviceIds);
        }

        return true;
    }

    /**
     * 单个用户退出批量家庭群（创建者不能离开）
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
    public function quitMultiGroups($userId, $groupIds) {
        if ($userId <= 0 || empty($groupIds) || !is_array($groupIds)) {
            return false;
        }

        $domain = new Domain_Group();
        $isCreatorGroupIds = $domain->getCreatorForMultiGroups($userId, $groupIds); // 找出当前用户是创建者的家庭群id
        $notCreatorGroupIds = array_diff($groupIds, $isCreatorGroupIds); // 找出当前用户不是创建者的家庭群id

        if (!empty($notCreatorGroupIds)) {
            // 退出家庭群
            $model = new Model_GroupMember();
            $model->quitMultiGroups($userId, $notCreatorGroupIds);
            
            // 刷新家庭群数量
            $domain->refreshMultiGroupMemberAmount($notCreatorGroupIds);
        }

        return true;
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

        $domain = new Domain_Group();
        $isCreator = $domain->getMultiCreatorsForMultiGroups($userIds, $groupIds); // 找出批量用户是创建者的家庭群id

        $all = array();
        foreach ($userIds as $aUserId) {
            foreach ($groupIds as $aGroupId) {
                $all[] = array('user_id' => $aUserId, 'group_id' => $aGroupId);
            }
        }

        $notCreator = $this->_getDiffArray2d($all, $isCreator); // 找出当前用户不是创建者的家庭群id

        if (!empty($notCreator)) {

            // 退出家庭群
            $model = new Model_GroupMember();

            $theUserIds = array();
            $theGroupIds = array();
            foreach ($notCreator as $aNotCreator) {
                $theUserIds[] = $aNotCreator['user_id'];
                $theGroupIds[] = $aNotCreator['group_id'];
            }
            $theUserIds = array_unique($theUserIds);
            $theGroupIds = array_unique($theGroupIds);
            
            $model->quitMultiUserAndMultiGroups($theUserIds, $theGroupIds);
            
            // 刷新家庭群数量
            $domain->refreshMultiGroupMemberAmount($theGroupIds);
        }

        return true;
    }

    private function _getDiffArray2d($allArray, $otherArray) {
        $rs = array();
        foreach ($allArray as $aArray) {
            foreach ($otherArray as $aOtherArray) {
                if ($aArray['user_id'] != $aOtherArray['user_id'] || $aArray['group_id'] != $aOtherArray['group_id']) {
                    $rs[] = array('user_id' => $aArray['user_id'], 'group_id' => $aArray['group_id']);
                }
            }
        }
        return $rs;
    }

    /**
     * 批量获取家庭组成员
     *
     * - SQl查询优化
     */
    public function getMultiAllMembers($groupIds) {
        $model = new Model_GroupMember();
        $rs = $model->getMultiAllMembers($groupIds);

        foreach ($rs as &$itemRef) {
            // $itemRef['user_id'] = intval($itemRef['user_id']);
            $itemRef['UUID'] = Domain_User_Helper::userId2UUID(intval($itemRef['user_id']));
            unset($itemRef['user_id']);

        }

        return array_values($rs);
    }

    /**
     * 清空全部家庭组成员 
     *
     * - 注意，此操作须谨慎
     */
    public function clearAllMembers($groupId, $exceptUserId = 0) {
        if ($groupId <= 0) {
            return;
        }

        $model = new Model_GroupMember();
        $model->clearAllMembers($groupId, $exceptUserId);
    }

    /**
     * 分页获取家庭圈成员列表
     *
     * - 关联获取，后期可使用缓存优化性能 
     */
    public function getList($groupId, $page, $perpage, $userId = 0, $detectDeviceType = 'all') {
        if ($groupId <= 0) {
            return false;
        }

        $model = new Model_GroupMember();

        $rs = array_values($model->getMemberList($groupId, $page, $perpage, $detectDeviceType));

        //批量获取成员个人信息
        if (!empty($rs)) {
            Domain_User_Helper::attachMultiSnapshot($rs);
        }

        $userAliass = array();
        if ($userId > 0) {
            //收集全部的用户ID
            $userIds = array();
            foreach ($rs as $item) {
                $userIds[] = $item['user_id'];
            }

            //批量获取别名
            $domainUserRelation = new Domain_User_Relation();
            $userAliass = $domainUserRelation->getMultiAlias($userId, $userIds);
        }

        //适配
        foreach ($rs as &$member) {
            $member['alias'] = isset($userAliass[$member['user_id']]) ? $userAliass[$member['user_id']] : '';

            unset($member['user_id'], $member['group_id'], $member['id']);
        }


        return $rs;
    }

    /**
     * 获取用户之间共同的家庭组
     * @param array $userIds
     * @return array 没有时为空
     */
    public function getCommonGroups($userIds, $detectDeviceType = 'all', $deleteState = Domain_Group_Helper::GROUP_NOT_DELETE) {
        if (empty($userIds)) {
            return array();
        }

        $model = new Model_GroupMember();
        return $model->getCommonGroups($userIds, $detectDeviceType, $deleteState);
    }

    /**
     * 获取用户加入的所有家庭组id
     */
    public function getAllGroupIdsByUserId($userId, $detectDeviceType = 'all', $deleteState = '') {
        $rs = array();
        $model = new Model_GroupMember();
        $rs = $model->getAllGroupIdsByUserId($userId, $detectDeviceType, $deleteState);
        return $rs;
    }
}
