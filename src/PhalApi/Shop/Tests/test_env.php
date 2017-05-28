<?php
/**
 * demo接口测试入口
 * @author dogstar 2015-01-28
 */
 
/** ---------------- 根目录定义，自动加载 ---------------- **/

require_once dirname(__FILE__) . '/../../Public/init.php';

DI()->loader->addDirs('Shop');

//日记纪录 - Explorer
DI()->logger = new PhalApi_Logger_Explorer(
	PhalApi_Logger::LOG_LEVEL_DEBUG | PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);

// 禁用缓存
DI()->cache = new PhalApi_Cache_None();

if (class_exists('Common_Kernal', FALSE)) {
    class Common_Kernal {

        public static function eixt($status = NULL) {
            echo "exit here ...\n";
        }
    }
}
