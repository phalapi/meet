<?php
/**
 * Model_Pay_Alipay
 * @author  2017-05-10 02:01:28
 */

class Model_Pay_Alipay extends PhalApi_Model_NotORM {

    protected function getTableName($id) {
        return 'pay_alipay';
    }
}
