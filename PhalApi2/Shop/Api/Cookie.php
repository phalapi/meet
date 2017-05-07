<?php

class Api_Cookie extends PhalApi_Api {

    public function set() {
        DI()->cookie->set('name', 'phalapi', $_SERVER['REQUEST_TIME'] + 600);
    }

    public function get() {
        return DI()->cookie->get('name');
    }

    public function delete() {
        DI()->cookie->delete('name');
    }
}
