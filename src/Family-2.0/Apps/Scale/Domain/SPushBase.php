<?php
/**
 * 推送基类 - 模板方法
 *
 * @author dogstar 20150711
 */

abstract class Domain_SPushBase {

    /**
     * 统一的推送模板方法
     */
    public function push($UUID) {
        $userId = Domain_User_Helper::UUID2UserId($UUID);

        if ($userId <= 0) {
            return 1;
        }

        $domainMotherInfo = new Domain_SMotherInfo();
        $infoEx = $domainMotherInfo->getInfoEx($userId);
        $infoEx['user_id'] = $userId;

        if (empty($infoEx)) {
            return 1;
        }

        //过滤不符合条件的用户
        if (!$this->varidateInfo($infoEx)) {
            return 2;
        }

        //过滤已有纪录的用户，以防重复推送
        $domainPushReocrd = new Domain_SPush_Record();
        if ($domainPushReocrd->hasPushBefore($userId, $this->getPushRecordType($infoEx))) {
            return 3;
        }

        //找到绑定的称
        $domainDevice = new Domain_Device_User();
        $deviceList = $domainDevice->getList($userId, Model_Device::DEVICE_TYPE_SCALE);
        if (empty($deviceList) || empty($deviceList[0]['binded_groups'][0]['group_id'])) {
            DI()->logger->error('no device or no group for user when push week', array('userId' => $userId));
            return 5;
        }
        $toPushWeekGroupId = $deviceList[0]['binded_groups'][0]['group_id']; //取第一个家庭组

        //动态图片
        $isPostFeed = $this->postFeed($userId, $infoEx, $toPushWeekGroupId);

        if (!$isPostFeed) {
            return 6;
        }

        //推送纪录
        $domainPushReocrd->takeRecord($userId, $this->getPushRecordType($infoEx));

        return 0;
    }

    /**
     * 验证用户是否满足推送的业务要求
     */
    protected function varidateInfo($infoEx) {
        return true;
    }

    /**
     * 发布动态
     */
    abstract protected function postFeed($userId, $infoEx, $toPushWeekGroupId);

    /**
     * 推送纪录类型
     */
    abstract protected function getPushRecordType($infoEx);

    /** -------------------- 内部功能函数(也为单元测试做好缝纫点) -------------------- **/

    /**
     * 获取怀孕第几月
     */
    protected function getMonth($infoEx) {
        return Domain_SKnowledge::expectBornDate2Month($infoEx['expect_born_date']);
    }

    /**
     * 获取怀孕第几周
     */
    protected function getWeekth($infoEx) {
        return Domain_SKnowledge::expectBornDate2Weekth($infoEx['expect_born_date']);
    }

    /**
     * 获取婴儿出生第几月
     */
    protected function getMonthForBaby($babyBirthday) {
        return Domain_SKnowledge::babyBirthday2Month($babyBirthday);
    }
}
