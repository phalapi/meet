<?php
/**
 * Model_Stock
 * @author  2017-05-10 01:39:33
 */

class Model_Stock extends PhalApi_Model_NotORM {

    protected function getTableName($id) {
        return 'stock';
    }
}
