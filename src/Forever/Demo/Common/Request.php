<?php

class Common_Request extends PhalApi_Request {

    public function getService() {
        $service = parent::getService();

        // 兼容默认格式
        if (strpos($service, '.')) {
            return $service;
        }

        // 定制后的格式：大写转换 ＋ 后缀
        $className = preg_replace("/(?:^|_)([a-z])/e", "strtoupper('\\0')", $service);
        $newService = $className . '.Go';

        return $newService;
    }
}
