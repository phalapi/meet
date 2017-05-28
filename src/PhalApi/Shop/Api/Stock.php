<?php
/**
 * Api_Stock
 * @author  2017-05-10 01:39:33
 */

class Api_Stock extends PhalApi_Api {

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
        $domain = new Domain_Stock();
        $domain->go();

        return $rs;
    }
}
