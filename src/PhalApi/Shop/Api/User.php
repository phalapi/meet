<?php

class Api_User extends PhalApi_Api {

    public function getRules() {
        return array(
            '*' => array(
                'code' => array('name' => 'code', 'require' => true, 'min' => 4, 'max' => 4),
            ),
            'login' => array(
                'username' => array('name' => 'username', 'require' => true),
                'password' => array('name' => 'password', 'require' => true, 'min' => 6),
            ),
        );
    }

    public function login() {
        return array('username' => $this->username, 'password' => $this->password);
    }                                    
}

