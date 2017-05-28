<?php
// echo $_GET['echostr'];
// die();

if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
    die('Access denied!');
}

require_once dirname(__FILE__) . '/../init.php';

//装载你的接口
DI()->loader->addDirs('Shop');

/** ---------------- 微信轻聊版 ---------------- **/

$robot = new Wechat_Lite('YourTokenHere...', true);
$rs = $robot->response();
$rs->output();
