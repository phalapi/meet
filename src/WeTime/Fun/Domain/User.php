<?php
/**
 * 用户领域业务类
 */

class Domain_User {

    public function getUserList($allUids) {
        $model = new Model_User();
        return $model->getUserList($allUids);
    }
}
