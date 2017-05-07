<?php
class Common_Request_Version {

    public static function formatVersion($value, $rule) {
        if (count(explode('.', $value)) < 3) {
            throw new PhalApi_Exception_BadRequest('版本号格式错误');
        }
        return $value;
    }
}
