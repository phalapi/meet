<?php
/**
 * 关注领域业务类
 */

class Domain_Follow {

    public function getFollowUids($userId) {
        $model = new Model_Follow();
        return $model->getFollowUids($userId);
    }
}
