<?php
/**
 * 家庭圈成员
 */

class Api_Group_Member extends PhalApi_Api {

	public function getRules() {
		return array(
            'getList' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'require' => true, 'min' => 1),
                'perpage' => array('name' => 'perpage', 'type' => 'int', 'default' => 10, 'min' => 1),
                'groupId' => array('name' => 'group_id', 'type' => 'int', 'require' => true, 'min' => 1),
                'deviceType' => array('name' => 'device_type', 'type' => 'enum', 'range' => array('all', 'cube', 'scale'), 'default' => 'all'),
            ),
            'joinGroup' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
                'groupNum' => array('name' => 'group_num', 'require' => true, 'min' => 4),
                'groupPwd' => array('name' => 'group_pwd', 'require' => true, 'min' => 4),
                'deviceType' => array('name' => 'device_type', 'type' => 'enum', 'range' => array('all', 'cube', 'scale'), 'default' => 'all'),
            ),
            'quitGroup' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
                'groupId' => array('name' => 'group_id', 'type' => 'int', 'require' => true, 'min' => 1),
            ),
            'removeGroupMember' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
                'groupId' => array('name' => 'group_id', 'type' => 'int', 'require' => true, 'min' => 1),
                'otherUUID' => array('name' => 'other_UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
            ),
        );
	}

    /**
     * 2.4.9 分页获取家庭圈成员列表
     */
    public function getList() {
        $rs = array('code' => Common_Def::CODE_OK, 'is_creator' => false, 'member_num' => 0, 'members' => array(), 'msg' => '');
        DI()->userLite->check(true);

        $domain = new Domain_Group_Member();
        $rs['members'] = $domain->getList($this->groupId, $this->page, $this->perpage, $this->userId, $this->deviceType);

        if (!empty($rs['members'])) {
            $groupDomain = new Domain_Group();
            $rs['member_num'] = $groupDomain->getGroupMemberAmount($this->userId, $this->groupId);

            $rs['is_creator'] = $groupDomain->isCreator($this->userId, $this->groupId);
        }

        return $rs;
    }

    /**
     * 加入家庭圈 
     */
    public function joinGroup() {
        $rs = array('code' => Common_Def::CODE_OK, 'group_id' => 0, 'msg' => '');

        DI()->userLite->check(true);

        //step 1. check
        $domain = new Domain_Group();
        $groupId = $domain->getGroupIdByGroupNum($this->groupNum);
        $rs['group_id'] = $groupId;
        if ($groupId <= 0) {
            $rs['code'] = 1;
            $rs['msg'] = T('group {number} not exists', array('number' => $this->groupNum));
            DI()->logger->debug('group not exists when join', 
                array('userId' => $this->userId, 'groupNum' => $this->groupNum));
            return $rs;
        }

        //step 2. check pwd
        if (!$domain->checkPassword($groupId, $this->groupPwd)) {
            $rs['code'] = 2;
            $rs['msg'] = T('group passwrod wrong');
            return $rs;
        }

        //step 3. check has joined
        $memberDomain = new Domain_Group_Member();
        if ($memberDomain->hasJoined($this->userId, $groupId, $this->deviceType)) {
            $rs['code'] = 3;
            $rs['msg'] = T('has joined the group memeber');
            return $rs;
        }

        //step 4. join
        $memberDomain->joinGroup($this->userId, $groupId);
        DI()->logger->info('user join group', 
            array('userId' => $this->userId, 'groupNum' => $this->groupNum, 'groupId' => $groupId));

        $rs['group_id'] = $groupId;

        return $rs;
    }

    /**
     * 退出家庭圈
     *
     * - 由用户自己主动退出离开
     * - 创建者不能离开
     *
     * @link http://git.oschina.net/family/wiki/wikis/2.4.10-%E9%80%80%E5%87%BA%E5%AE%B6%E5%BA%AD%E5%9C%88
     */
    public function quitGroup() {
        $rs = array('code' => Common_Def::CODE_OK, 'msg' => '');

        DI()->userLite->check(true);

        $domain = new Domain_Group();
        if ($domain->isCreator($this->userId, $this->groupId)) {
            $rs['code'] = 1;
            $rs['msg'] = T('group creator can not leave');

            return $rs;
        }

        $memDomain = new Domain_Group_Member();
        $memDomain->quitGroup($this->userId, $this->groupId);

        DI()->logger->info('user quit group', 
            array('userId' => $this->userId, 'groupId' => $this->groupId));

        return $rs;
    }

    /**
     * 创建者删除家庭圈某用户
     *
     * - 由群主操作
     * - 不能删除群主
     *
     * @link http://git.oschina.net/family/wiki/wikis/2.4.11-%E5%88%A0%E9%99%A4%E5%AE%B6%E5%BA%AD%E5%9C%88%E6%9F%90%E7%94%A8%E6%88%B7
     */
    public function removeGroupMember() {
        $rs = array('code' => Common_Def::CODE_OK, 'msg' => '');

        DI()->userLite->check(true);

        $domain = new Domain_Group();
        if (!$domain->isCreator($this->userId, $this->groupId)) {
            $rs['code'] = 1;
            $rs['msg'] = T('not group creator');

            return $rs;
        }

        $otherUserId = Domain_User_Helper::UUID2userId($this->otherUUID);
        if ($this->userId == $otherUserId) {
            $rs['code'] = 2;
            $rs['msg'] = T('group creator can not leave');

            return $rs;
        }

        $memDomain = new Domain_Group_Member();
        $memDomain->quitGroup($otherUserId, $this->groupId);

        DI()->logger->info('group creator remove member',
            array('creatorId' => $this->userId, 'userId' => $otherUserId, 'groupId' => $this->groupId));

        return $rs;
    }
}
