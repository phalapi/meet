<?php
/**
 * 设备接口类
 */

class Api_Device_Scale extends PhalApi_Api {

    public function getRules() {
        return array(
            'setCommonInfo' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
                'age' => array('name' => 'age', 'type' => 'int', 'min' => 0, 'max' => 120, 'default' => 0),
                'birthday'=> array('name' => 'birthday', 'type' => 'int', 'min' => 0, 'max' => 1999999999, 'default' => 0),
                'height' => array('name' => 'height', 'type' => 'int', 'min' => 0, 'max' => 250000, 'default' => 0),
                'weight' => array('name' => 'weight', 'type' => 'int', 'min' => 0, 'max' => 250000, 'default' => 0),
                'gender' => array('name' => 'gender', 'type' => 'enum', 'range' => array('male', 'female', '---'), 'default' => '---'),
                'type' => array('name' => 'type', 'type' => 'enum', 'range' => array(0, 1, 2), 'default' => 0),
                'newestWeight' => array('name' => 'newest_weight', 'type' => 'int', 'min' => 0, 'max' => 250000, 'default' => 0),
            ),
            'getInfo' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
            ),
            'uploadWeight' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
                'weight' => array('name' => 'weight', 'type' => 'int', 'min' => 0, 'max' => 250000, 'default' => 0, 'require' => true),
                'type' => array('name' => 'type', 'type' => 'enum', 'range' => array(0, 1, 2), 'default' => 0, 'require' => true),
                'babyPosition' => array('name' => 'baby_position', 'type' => 'int', 'default' => 0, 'require' => false),
                'babyWeight' => array('name' => 'baby_weight', 'type' => 'int', 'min' => 0, 'max' => 250000, 'default' => 0, 'require' => false),
            ),
            'getWeightList' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
                'type' => array('name' => 'type', 'type' => 'enum', 'range' => array(0, 1, 2), 'default' => 0, 'require' => true),
                'babyPosition' => array('name' => 'baby_position', 'type' => 'int', 'default' => 0, 'require' => true),
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'require' => true, 'min' => 1),
                'perpage' => array('name' => 'perpage', 'type' => 'int', 'default' => 10, 'min' => 1),
                'sort' => array('name' => 'sort', 'type' => 'enum', 'range' => array('from_last', 'from_first'), 'default' => 'from_last', 'min' => 1, 'require' => false),
                'getNewestForEveryday' => array('name' => 'get_newest_for_everyday', 'type' => 'int', 'range' => array(0, 1), 'default' => 1, 'min' => 0, 'require' => false),
            ),
            'getWeightListBetweenDate' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
                'type' => array('name' => 'type', 'type' => 'enum', 'range' => array(0, 1, 2), 'default' => 0, 'require' => true),
                'babyPosition' => array('name' => 'baby_position', 'type' => 'int', 'default' => 0, 'require' => true),
                'littleDate' => array('name' => 'little_date', 'type' => 'int', 'default' => 0, 'require' => true),
                'bigDate' => array('name' => 'big_date', 'type' => 'int', 'default' => 0, 'require' => true),
            ),
            'calculateStatus' => array(
                'UUID' => array('name' => 'UUID', 'type' => 'string', 'default' => '00000000000000000000000000000000000000000000000000', 'require' => true, 'min' => 50, 'max' => 50,),
                'type' => array('name' => 'type', 'type' => 'enum', 'range' => array(0, 1, 2), 'require' => true),
                'babyPosition' => array('name' => 'baby_position', 'type' => 'int', 'default' => 0, 'require' => true),
                'weights' => array('name' => 'weights', 'type' => 'array', 'format' => 'json', 'default' => '[]', 'require' => true),
            ),
        );
    }

    /**
     * 设置秤的基本个人信息
     */
    public function setCommonInfo() {
        $rs = array('code' => Common_Def::CODE_OK, 'msg' => '');

        DI()->userLite->check(true);

        $domain = new Domain_SInfo();

        if (!$domain->hasUserData($this->userId)) {
            $domain->initDefaultUserData($this->userId);
        }

        $updateData = array();

        if ($this->birthday > 0) {
            $updateData['birthday'] = $this->birthday;
            $updateData['age'] = Domain_SKnowledge::calculateAgeWithTimestamp($this->birthday);
        } else {
            // 兼容旧数据
            if ($this->age > 0) {
                $updateData['age'] = $this->age;
            }
        }
        if ($this->height > 0) {
            $updateData['height'] = $this->height;
        }
        if ($this->weight > 0) {
            $updateData['weight'] = $this->weight;
        }
        if ($this->gender != '---') {
            $updateData['gender'] = $this->gender;
        }
        if ($this->type >= 0) {
            $updateData['type'] = $this->type;
        }
        if ($this->newestWeight > 0) {
            $updateData['newestWeight'] = $this->newestWeight;
        }

        if (!empty($updateData)) {
            $domain->freeUpdate($this->userId, $updateData);

            //MQ队列推送 - 周营养计划
            DI()->taskLite->add('Nutrition_SWeek.Push', array('other_UUID' => $this->UUID));
            //月
            DI()->taskLite->add('Nutrition_SMonth.Push', array('other_UUID' => $this->UUID));
            //孕妇周报
            DI()->taskLite->add('Nutrition_SWeekForPregnant.Push', array('other_UUID' => $this->UUID));

            if ($this->type == Domain_SInfo::TYPE_PREGNANT) {
                DI()->taskLite->add('Nutrition_SSpecialScene', array('other_UUID' => $this->UUID));
            }
            if ($this->type == Domain_SInfo::TYPE_MOTHER) {
                DI()->taskLite->add('Nutrition_SSpecialScene.PushAfterHaveBaby', array('other_UUID' => $this->UUID));
            }
        }

        // type == 2时，weight表示产后体重
        if ($this->type == 2) {
            $motherDomain = new Domain_SMotherInfo();
            if (!$motherDomain->hasUserData($this->userId)) {
                $motherDomain->initDefaultUserData($this->userId);

                DI()->logger->info('init default smother data', array('userId' => $this->userId));
            }
            $updateMotherData = array();
            if ($this->weight > 0) {
                $updateMotherData['weight_after_born'] = $this->weight;
            }
            if (!empty($updateMotherData)) {
                $motherDomain->freeUpdate($this->userId, $updateMotherData);
            }
        }


        $rs['msg'] = T('set scale info success');
        return $rs;
    }

    /**
     * 获取秤的个人信息
     */
    public function getInfo() {
        $rs = array('code' => Common_Def::CODE_OK, 'info' => array(), 'msg' => '');
        
        DI()->userLite->check(true);

        $domain = new Domain_SInfo();
        $info = $domain->getInfo($this->userId);
        
        if (empty($info)) {
            $rs['code'] = 1;
            $rs['msg'] = T('failed to get scale user info');
            return $rs;
        }

        if ($info['type'] == Domain_SInfo::TYPE_PREGNANT || $info['type'] == Domain_SInfo::TYPE_MOTHER) {
            $motherDomain = new Domain_SMotherInfo();
            $info = array_merge($info, $motherDomain->getInfo($this->userId));
            unset($info['weight']);
        }

        if ($info['type'] == Domain_SInfo::TYPE_MOTHER) {
            $babyDomain = new Domain_SBabyInfo();
            $babyRs = $babyDomain->getInfoByMotherUserId($this->userId);
            $info['babys'] = $babyRs;
        }

        $rs['info'] = $info;

        return $rs;
    }

    /**
     * 上传体重
     */
    public function uploadWeight() {
        $rs = array('code' => Common_Def::CODE_OK, 'msg' => '');

        if ($this->type != 2 && $this->babyPosition != 0) {
            throw new PhalApi_Exception_BadRequest(T('baby position is not 0'));
        }

        DI()->userLite->check(true);

        $domain = new Domain_SWeight();
        $domain->addWeight($this->userId, $this->weight, $this->type, $this->babyPosition, $this->babyWeight);

        if ($this->type == 1) {
            // 设置最新的体重（type为1时，表示孕妇的，妈妈的数据是存在 smother 表里；type为2时，妈妈的数据是存在 suser 表里）
            $motherDomain = new Domain_SMotherInfo();
            $motherDomain->initDefaultUserDataIfNoUserData($this->userId);
            if ($this->weight > 0) {
                $motherDomain->freeUpdate($this->userId, array('newest_weight' => $this->weight));
            }
        } else {
            // 设置最新的体重（type为1时，表示孕妇的，妈妈的数据是存在 smother 表里；type为2时，妈妈的数据是存在 suser 表里）
            $commonDomain = new Domain_SInfo();
            $commonDomain->initDefaultUserDataIfNoUserData($this->userId);
            if ($this->weight > 0) {
                $commonDomain->freeUpdate($this->userId, array('newest_weight' => $this->weight));
            }
        } 

        if ($this->type == 2) {
            // 设置宝宝最新的体重
            $babyDomain = new Domain_SBabyInfo();
            $babyDomain->initDefaultUserDataIfNoUserData($this->userId, $this->babyPosition);
            if ($this->babyWeight > 0) {
                $babyDomain->freeUpdate($this->userId, $this->babyPosition, array('newest_weight' => $this->babyWeight));
            }
        }

        return $rs;
    }

    /**
     * 2.7.6 获取体重列表
     *  - 注意：当 type == 2，并且 babyPosition > 0 时，取的是宝宝的体重
     */
    public function getWeightList() {
        $rs = array('code' => Common_Def::CODE_OK, 'msg' => '');

        DI()->userLite->check(true);

        if ($this->type != 2 && $this->babyPosition != 0) {
            throw new PhalApi_Exception_BadRequest(T('baby position is not 0'));
        }

        $domain = new Domain_SWeight();

        $rs['first_scale_date'] = $domain->getFirstScaleDate($this->userId, $this->type, $this->babyPosition);
        // 当 type == 2，并且 babyPosition > 0 时，取的是宝宝的体重
        $rs['weights'] = $domain->getList($this->userId, $this->type, $this->babyPosition, $this->page, $this->perpage, $this->sort, $this->getNewestForEveryday);

        if (empty($rs['weights'])) {
            return $rs;
        }

        $domainSScale = new Domain_SScale();
        $domainSScale->getMixedStatus($this->userId, $this->type, $this->babyPosition, $rs);

        return $rs;
    }

    /**
     *  2.7.7 获取体重列表（取两个日期间的数据）
     *  - 注意：当 type == 2，并且 babyPosition > 0 时，取的是宝宝的体重
     */
    public function getWeightListBetweenDate() {
        $rs = array('code' => Common_Def::CODE_OK, 'msg' => '');

        DI()->userLite->check(true);

        if ($this->type != 2 && $this->babyPosition != 0) {
            throw new PhalApi_Exception_BadRequest(T('baby position is not 0'));
        }

        if ($this->littleDate > $this->bigDate) {
            $rs['code'] = 5;
            $rs['msg'] = T('little_date bigger than big_date');
            return $rs;
        }

        $domain = new Domain_SWeight();

        $rs['first_scale_date'] = $domain->getFirstScaleDate($this->userId, $this->type, $this->babyPosition);
        // 当 type == 2，并且 babyPosition > 0 时，取的是宝宝的体重
        $rs['weights'] = $domain->getListBetweenDate($this->userId, $this->type, $this->babyPosition, $this->littleDate, $this->bigDate);

        if (empty($rs['weights'])) {
            return $rs;
        }

        $domainSScale = new Domain_SScale();
        $domainSScale->getMixedStatus($this->userId, $this->type, $this->babyPosition, $rs);

        return $rs;
    }


    /**
     *  2.7.8 计算体重状态
     *  - 注意：当 type == 2，并且 babyPosition > 0 时，取的是宝宝的体重
     */
    public function calculateStatus() {
        $rs = array('code' => Common_Def::CODE_OK, 'weights' => array(), 'msg' => '');

        DI()->userLite->check(true);
        
        if ($this->type == '') {
            throw new PhalApi_Exception_BadRequest(T('type is empty'));
        }
        
        if (empty($this->weights) || !is_array($this->weights)) {
            $rs['code'] = 5;
            $rs['msg'] = T('weights is empty');
            return $rs;
        }

        $rs['weights'] = $this->weights;

        $domainSScale = new Domain_SScale();
        $domainSScale->getMixedStatus($this->userId, $this->type, $this->babyPosition, $rs);

        // tips
        if ($this->type != 2 || $this->babyPosition == 0) {
            $domainSScale->formatWeightTips($rs['weights'], $this->type);
        }

        return $rs;
    }
}





