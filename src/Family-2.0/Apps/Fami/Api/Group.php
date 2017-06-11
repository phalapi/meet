<?php

class Api_Group extends PhalApi_Api {

    public function getRules() {
        return array(
            'getGroupInfo' => array(
                'groupId' => array('name' => 'group_id', 'require' => true),
            ),
        );
    }

    public function getGroupInfo() {
        $rs = array('code' => Common_Def::CODE_OK, 'msg' => '');

        $domain = new Domain_Group();
        $rs['info'] = $domain->getGroupInfoByGroupId($this->groupId);

        return $rs;
    }
}
