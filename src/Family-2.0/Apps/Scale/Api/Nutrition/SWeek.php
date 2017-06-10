<?php

class Api_Nutrition_SWeek extends PhalApi_Api {

    public function getRules() {
        return array(
            '*' => array(
                'otherUUID' => array('name' => 'other_UUID', 'require' => true, 'min' => 50, 'max' => 50,),
            ),
        );
    }

    public function push() {
        $rs = array('code' => Common_Def::CODE_OK, 'msg' => '');

        $domain = new Domain_SPush_Week();
        $rs['code'] = $domain->push($this->otherUUID);

        return $rs;
    }

    public function push39() {
        $rs = array('code' => Common_Def::CODE_OK, 'msg' => '');

        $domain = new Domain_SPush_Week39();
        $rs['code'] = $domain->push($this->otherUUID);

        return $rs;
    }
}

