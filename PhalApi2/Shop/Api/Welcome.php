<?php
class Api_Welcome extends PhalApi_Api {

    public function getRules() {
        return array(
            'say' => array(
                'version' => array('name' => 'version', 'type' => 'callable', 'callback' => 'Common_Request_Version::formatVersion'),
            )
        );
    }

    public function say() {
        DI()->tracer->mark('欢迎光临');

        //throw new Exception('这是一个演示异常调试的示例', 501);

        return 'Hello World';
    }
}

