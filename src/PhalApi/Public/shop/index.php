<?php
/**
 * Shop 统一入口
 */

require_once dirname(__FILE__) . '/../init.php';

// 装载你的接口
DI()->loader->addDirs('Shop');

DI()->request = new Common_Request_Ch1();

DI()->_formatterEmail = 'Common_Request_Email';

// 微信签名验证服务
//DI()->filter = 'Common_Request_WeiXinFilter';

// XML返回
//DI()->response = 'Common_Response_XML';
// 调整返回结构
//DI()->response = 'Common_Response_Result';

$config = array('domain' => '.phalapi.net');
DI()->cookie = new PhalApi_Cookie($config);
$config = array('domain' => '.phalapi.net', 'crypt' => new Common_Crypt_Base64());
DI()->cookie = new PhalApi_Cookie_Multi($config);

// 显式初始化，并调用分发
//DI()->fastRoute = new FastRoute_Lite();
//DI()->fastRoute->dispatch();

/** ---------------- 响应接口请求 ---------------- **/

$api = new PhalApi();
$rs = $api->response();
$rs->output();

