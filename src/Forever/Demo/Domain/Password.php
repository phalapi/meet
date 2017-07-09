<?php

class Domain_Password {

    const CRYPT_KEY = '06633f94d3';
    protected $mcrypt;

    public function __construct() {
        $iv = DI()->config->get('sys.crypt.mcrypt_iv');
        $this->mcrypt = new PhalApi_Crypt_MultiMcrypt($iv);
    }

    public function encrypt($pass) {
        return $this->mcrypt->encrypt($pass, self::CRYPT_KEY);
    }

    public function decrypt($encryptPass) {
        return $this->mcrypt->decrypt($encryptPass, self::CRYPT_KEY);
    }
}
