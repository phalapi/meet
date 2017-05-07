<?php
class Common_Request_Base64Data extends PhalApi_Request {

    public function genData($data) {
        if (!isset($data) || !is_array($data)) {
            $data = $_POST; //改成只接收POST
        }

        return isset($data['data']) ? base64_decode($data['data']) : array();
    }
}
