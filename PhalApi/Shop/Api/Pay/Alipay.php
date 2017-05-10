<?php
/**
 * Api_Pay_Alipay
 * @author  2017-05-10 02:01:28
 */

class Api_Pay_Alipay extends PhalApi_Api {

    public function getRules() {
        return array(
            'go' => array(
            ),
        );
    }

    /**
     * go接口
     * @desc go接口描述
     * @return int code 状态码，0表示成功，非0表示失败
     * @return string msg 状态提示
     */
    public function go() {
        $rs = array('code' => 0, 'msg' => '');

        // TODO
        $domain = new Domain_Pay_Alipay();
        $domain->go();

        return $rs;
    }
}
