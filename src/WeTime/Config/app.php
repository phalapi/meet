<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        // 验签
        'service' => array(
            'name' => 'service', 'type' => 'string', 'require' => true, 'default' => 'Default.Index',
        ),
        'sign' => array(
            'name' => 'sign', 'type' => 'string', 'require' => true,
        ),

        // 客户端类型：ios/android/pc
        'client' => array(
            'name' => 'client', 'type' => 'enum', 'default' => 'pc', 'require' => false, 'range' => array('ios', 'android', 'pc'),
        ),
        // 客户端App版本号，如：1.0.1
        'version' => array(
            'name' => 'version', 'type' => 'string', 'default' => '', 'require' => false,
        ),

        // 登录信息
        'userId' => array(
            'name' => 'user_id', 'type' => 'int', 'default' => 0, 'require' => false,
        ),
    ),

    /**
     * 接口服务白名单，格式：接口服务类名.接口服务方法名
     *
     * 示例：
     * - *.*            通配，全部接口服务，慎用！
     * - Default.*      Api_Default接口类的全部方法
     * - *.Index        全部接口类的Index方法
     * - Default.Index  指定某个接口服务，即Api_Default::Index()
     */
    'service_whitelist' => array(
        'Default.Index',
    ),
);
