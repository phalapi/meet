<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        //签名
        //'sign' => array(
        //    'name' => 'sign', 'require' => true,
        //),
        //客户端App版本号，默认为：1.4.0
        //'version' => array(
        //    'name' => 'version', 'default' => '1.4.0',  
        //),
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

    /**
     * 七牛相关配置
     */
    'Qiniu' =>  array(
        //统一的key
        'accessKey' => '*****',
        'secretKey' => '****',
        //自定义配置的空间
        'space_bucket' => '自定义配置的空间',
        'space_host' => 'http://XXXXX.qiniudn.com',
    ),

    /**
     * 微信扩展 - 插件注册
     */
    'Wechat' => array(
        'plugins' => array(
            Wechat_InMessage::MSG_TYPE_TEXT => array('Plugin_Money', 'Plugin_Menu',),
            Wechat_InMessage::MSG_TYPE_IMAGE => array(),
            Wechat_InMessage::MSG_TYPE_VOICE => array(),
            Wechat_InMessage::MSG_TYPE_VIDEO => array(),
            Wechat_InMessage::MSG_TYPE_LOCATION => array(),
            Wechat_InMessage::MSG_TYPE_LINK => array(),
            Wechat_InMessage::MSG_TYPE_EVENT => array(),
            Wechat_InMessage::MSG_TYPE_DEVICE_EVENT => array(),
            Wechat_InMessage::MSG_TYPE_DEVICE_TEXT => array(),
        ),
    ),

	/**
	 * 扩展类库 - 快速路由配置
	 */
    'FastRoute' => array(
         /**
          * 格式：array($method, $routePattern, $handler)
          *
          * @param string/array $method 允许的HTTP请求方烤鸡，可以为：GET/POST/HEAD/DELETE 等
          * @param string $routePattern 路由的正则表达式
          * @param string $handler 对应PhalApi中接口服务名称，即：?service=$handler
          */
        'routes' => array(
            array('GET', '/shop/comment/{id:\d+}', 'Comment.Get'),
            array('PUT', '/shop/comment', 'Comment.Add'),
            array('POST', '/shop/comment', 'Comment.Add'),
            array('DELETE', '/shop/comment/{id:\d+}', 'Comment.Delete'),
            //array('GET', '/user/get_base_info/{user_id:\d+}', 'User.GetBaseInfo'),
            //array('GET', '/user/get_multi_base_info/{user_ids:[0-9,]+}', 'User.GetMultiBaseInfo'),
        ),
    ),
);
