<?php
class Common_Request_Email implements PhalApi_Request_Formatter {

    public function parse($value, $rule) {
        if (!preg_match('/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/', $value)) {
            throw new PhalApi_Exception_BadRequest('邮箱地址格式错误');
        }

        return $value;
    }
}  
