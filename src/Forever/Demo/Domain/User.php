<?php

class Domain_User {

    public function login($user, $pass) {
        $servicePass = new Domain_Password();
        $encryptPass = $servicePass->encrypt($pass);

        $model = new Model_User();
        return $model->login($user, $encryptPass);
    }
}
