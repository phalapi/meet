<?php

class Api_Statistics extends PhalApi_Api {

    public function getRules() {
        return array(
            'report' => array(
                'username' => array('name' => 'username', 'require' => true),
                'msg' => array('name' => 'msg', 'require' => true),
            ),
        );
    }

    public function report() {
        DI()->logger->info($this->username, $this->msg);
    }                                    
}

