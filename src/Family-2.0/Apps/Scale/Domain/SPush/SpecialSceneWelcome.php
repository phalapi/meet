<?php
/**
 * 特定场景推送
 */

class Domain_SPush_SpecialSceneWelcome extends Domain_SPushBase {

    /**
     * 验证用户是否满足推送的业务要求
     */
    protected function varidateInfo($infoEx) {
        return $infoEx['type'] == Domain_SInfo::TYPE_PREGNANT;
    }

    /**
     * 发布动态
     */
    protected function postFeed($userId, $infoEx, $toPushWeekGroupId) {
        $specialCfg = DI()->config->get('push.special_scene.6');

        $domainFeed = new Domain_Feed();
        $postUserId = DI()->config->get('app.NPC.special_scene.userId');
        $typeStruct = json_encode(
            array(
                'content_type' => 6,
                'url' => $specialCfg['jump_url'],
            )
        );
        $content = $specialCfg['content'];
        $feedId = $domainFeed->postBaseFeed($postUserId, $toPushWeekGroupId, $content, 0, 0, 'web', $typeStruct);

        if ($feedId > 0 && !empty($specialCfg['pics']) && is_array($specialCfg['pics'])) {
            $domainFeed->attachFeedPics($feedId, $specialCfg['pics']);
        }

        DI()->logger->debug('success to push welcome for special scene', array('userId' => $userId));

        return $feedId > 0;
    }

    /**
     * 推送纪录类型
     */
    protected function getPushRecordType($infoEx) {
        return 'ss_welcome';
    }
}
