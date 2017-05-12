<?php
require_once dirname(__FILE__) . '/../init.php';

// 装载你的接口
DI()->loader->addDirs('Shop');

$server = new PHPRPC_Lite();
$server->response();
