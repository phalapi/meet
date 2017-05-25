<?php

class Common_Kernal {

    public static function eixt($status = NULL) {
        if ($status === NULL) {
            exit();
        } else {
            exit($status);
        }
    }
}
