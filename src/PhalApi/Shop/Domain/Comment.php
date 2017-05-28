<?php

class Domain_Comment {

    public function get($id) {
        $model = new Model_Comment();
        $rs = $model->get($id);
        // 判断数据有效性
        return !empty($rs) ? $rs : array();
    }
}
