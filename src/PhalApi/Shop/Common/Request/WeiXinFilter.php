<?php

class Common_Request_WeiXinFilter implements PhalApi_Filter {

    public function check() {
        $signature = DI()->request->get('signature');
        $timestamp = DI()->request->get('timestamp');
        $nonce = DI()->request->get('nonce');  

        $token = 'Your Token Here ...'; // TODO
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1( $tmpStr );

        if ($tmpStr != $signature) {
            throw new PhalApi_Exception_BadRequest('wrong sign', 1);
        }
    }
}
