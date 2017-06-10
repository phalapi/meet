<?php
/**
 * 每周营养计划推送
 *
 * @author dogstar 20150705
 */

class Domain_SPush_Week extends Domain_SPushBase {

    /**
     * 验证用户是否满足推送的业务要求
     */
    protected function varidateInfo($infoEx) {
        if ($infoEx['gender'] != Domain_SInfo::GENDER_FEMALE) {
            return false;
        }
        if ($infoEx['type'] != Domain_SInfo::TYPE_PREGNANT) {
            return false;
        }
        if ($infoEx['height'] <= 1200) { //1.2 m
            return false;
        }
        if (($infoEx['weight'] / 1000) <= 30) { //30 kg
            return false;
        }
        if ($infoEx['age'] <= 18) { //18岁
            return false;
        }

        if ($infoEx['expect_born_date'] <= 0 || $infoEx['expect_born_date'] < $_SERVER['REQUEST_TIME']) {
            return false;
        }
        if ($infoEx['weight_before_born'] <= 0) {
            return false;
        }

        return true;
    }

    /**
     * 发布动态
     */
    protected function postFeed($userId, $infoEx, $toPushWeekGroupId) {
        $weekth = $this->getWeekth($infoEx);
        $weekCfg = DI()->config->get('push.week');

        //体重与文案
        $nextWeekNeedCAL = 0;
        $nextWeekContent = '';

        $lastWeekWeight = $this->getLastWeekWeight($userId);
        if ($lastWeekWeight <= 0) {
            //上周无秤重
            $nextWeekNeedCAL = Domain_SKnowledge::calPregnantCAL(
                $infoEx['weight_before_born'] / 1000, $infoEx['height'] / 100, $weekth); //TODO 双胞胎？ 单位 ？
            $nextWeekContent = $weekCfg['message']['a'];
        } else {
            //上周有秤重
            $nextWeekNeedCAL = Domain_SKnowledge::getNextWeekNeedCAL(
                $infoEx['weight_before_born'] / 1000, $infoEx['height'] / 100, $weekth, $lastWeekWeight / 1000); //TODO 双胞胎？单位 ？

            $BMILevel = Domain_SKnowledge::calBMILevel($infoEx['weight_before_born'] / 1000, $infoEx['height'] / 100);
            if ($BMILevel == Domain_SKnowledge::BMI_THIN) {
                $nextWeekContent = $weekCfg['message']['b'];
            } else if ($BMILevel == Domain_SKnowledge::BMI_FAT) {
                $nextWeekContent = $weekCfg['message']['c'];
            } else {
                $nextWeekContent = $weekCfg['message']['d'];
            }
        }

        //随机选择一个合适的食谱
        $domainNutritionRecipe = new Domain_Nutrition_SRecipe();
        $recipeId = $domainNutritionRecipe->extractRandomOneByCalorie($nextWeekNeedCAL);

        if ($recipeId <= 0) {
            DI()->logger->error('no such recipe for calorie when push week', array('calorie' => $nextWeekNeedCAL));
            return false;
        }

        $domainFeed = new Domain_Feed();
        $postUserId = DI()->config->get('app.NPC.week_push.userId');
        $typeStruct = json_encode(
        	array(
        		'content_type' => $weekCfg['content_type'],
        		'recipe_id' => $recipeId,
        		'week_num' => $weekth,
        		'url' => sprintf($weekCfg['jump_url'], $recipeId, $weekth),
        	)
        );
        $feedId = $domainFeed->postBaseFeed($postUserId, $toPushWeekGroupId, $nextWeekContent, 0, 0, 'web', $typeStruct);
        DI()->logger->debug('success to post feed when push week', 
            array('userId' => $userId, 'postUserId' => $postUserId, 'groupId' => $toPushWeekGroupId, 'content' => $nextWeekContent, 'feedId' => $feedId));

        //动态图片
        if (!empty($weekCfg['pics']) && is_array($weekCfg['pics'])) {
        	$domainFeed->attachFeedPics($feedId, $weekCfg['pics']);
        }

        return true;
    }

    /**
     * 推送纪录类型
     */
    protected function getPushRecordType($infoEx) {
        return sprintf('week_%s', $this->getWeekth($infoEx));
    }

    /**
     * 取上周体重的平均值
     */
    protected function getLastWeekWeight($userId) {
        $domain = new Domain_SWeight();

        $littleDate = date('Ymd', strtotime('-1 week', $_SERVER['REQUEST_TIME']));
        $bigDate = date('Ymd', strtotime('-1 days', $_SERVER['REQUEST_TIME']));

        $weights = $domain->getListBetweenDate($userId, Domain_SInfo::TYPE_PREGNANT, 0, $littleDate, $bigDate);

        $totalWeight = 0;
        foreach ($weights as $item) {
            $totalWeight += $item['weight'];
        }

        return $totalWeight != 0 ? intval($totalWeight / count($weights)) : 0;
    }
}
