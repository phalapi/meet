<?php

class Domain_Group {

    public function getGroupInfoByGroupId($groupId) {
        $model = new Model_Group();
        $row = $model->getGroupInfoByGroupId($groupId);
        return $row;
    }
}
