<?php

class Plugin_Money implements Wechat_Plugin_Text {

    public function handleText($inMessage, &$outMessage) {
        $outMessage = new Wechat_OutMessage_News();

        $item = new Wechat_OutMessage_News_Item();
        $item->setTitle('让红包飞~')
            ->setDescription(sprintf('您已领取到一个%d元红包~', rand(1, 100)))
            ->setPicUrl('http://webtools.qiniudn.com/172906_61c8663a_121026.jpeg')
            ->setUrl('https://www.phalapi.net/');

        $outMessage->addItem($item);
    }
}
