<?php
/**
 * 推送类的计划任务基类
 *
 * @author dogstar 20150715
 */

abstract class TMyTrigger_PushBase implements Task_Progress_Trigger {

    public function fire($params) {
        //取全部待推送的用户
        $suser = $this->getWaitTOPushUserORM();

        $mq = new Task_MQ_Array();
        $runner = new Task_Runner_Local($mq);
        $service = $this->getService();

        $num = 0;

        while (($row = $suser->fetch())) {
            $num ++;

            $UUID = Domain_User_Helper::userId2UUID($row['user_id']);
            $mq->add($service, array('other_UUID' => $UUID, 'app_key' => 'mini', 'sign' => '***'));

            $rs = $runner->go($service);

            DI()->logger->debug($service, array('userId' => $row['user_id'], 'UUID' => $UUID, 'rs' => $rs));
        }

        return $num;
    }

    /**
     * 取全部待推送的用户
     * @return NotORM
     */
    abstract protected function getWaitTOPushUserORM();

    /**
     * 取服务名称
     */
    abstract protected function getService();
}
