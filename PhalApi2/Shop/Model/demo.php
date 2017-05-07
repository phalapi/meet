<?php

class Model_Demo extends PhalApi_Model_NotORM {

    protected function getTableName($id) {
        $tableName = 'demo';
        if ($id !== null) {
            $tableName .= '_' . ($id % 3);
        }
        return $tableName;
    }

    public function getNameById($id) {
        $row = $this->getORM($id)->select('name')->fetchRow();
        return !empty($row) ? $row['name'] : '';
    }
}
