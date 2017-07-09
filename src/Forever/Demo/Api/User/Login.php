<?php
/**
 * 用户登录
 * @author dogstar 20170622
 */

class Api_User_Login extends PhalApi_Api {

    public function getRules() {
        return array(
            'go' => array(
                'user' => array(
                    'name' => 'user',
                    'require' => true,
                    'min' => '1',
                    'desc' => '登录账号',
                ),
                'pass' => array(
                    'name' => 'pass',
                    'require' => true,
                    'min' => '6',
                    'desc' => '登录密码',
                ),
            ),
        );
    }

    /**
     * 用户登录接口
     * @desc 根据账号和密码进行登录，成功后返回凭证
     *
     * @return int      code    业务操作码，为0时表示成功，非0时表示登录失败
     * @return int      user_id 用户ID
     * @return string   token   登录凭证
     * @return string   tips    文案提示信息
     */
    public function go() {
        $rs = array('code' => 1, 'user_id' => 0, 'token' => '', 'tips' => '');

        $domain = new Domain_User();
        $userId = $domain->login($this->user, $this->pass);

        if ($userId <= 0) {
            $rs['tips'] = '登录失败，用户名或密码错误！';
            return $rs;
        }

        $token = DI()->userLite->generateSession($userId);

        $rs['code'] = 0;
        $rs['user_id'] = $userId;
        $rs['token'] = $token;

        return $rs;
    }
}
