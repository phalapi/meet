<?php
/**
 * 周营养计划推送
 *
 * @author dogstar 20150715
 */

class TMyTrigger_WeekPush extends TMyTrigger_PushBase {

    protected function getWaitTOPushUserORM() {
        return DI()->notorm->suser
            ->select('user_id')
            ->where('type', 1)
            ->where('height > ?', 0)
            ->where('weight > ?', 0);
    }

    protected function getService() {
        return 'Nutrition_SWeek.Push';
    }
}
