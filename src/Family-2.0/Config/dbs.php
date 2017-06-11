<?php
/**
 * 分库分表的自定义数据库路由配置
 * 
 * @author: dogstar <chanzonghuang@gmail.com> 2015-02-09
 */

return array(
    /**
     * DB数据库服务器集群
     */
    'servers' => array(
        'db_A' => array(                         //服务器标记
            'host'      => $_ENV['FAMILY_V2_DB_HOST'],                 //数据库域名
            'name'      => $_ENV['FAMILY_V2_DB_NAME'],                   //数据库名>字
            'user'      => $_ENV['FAMILY_V2_DB_USER'],                   //数据库用>户名
            'password'  => $_ENV['FAMILY_V2_DB_PASS'],                   //数据库密>码
            'port'      => $_ENV['FAMILY_V2_DB_PORT'],                   //数据库端>口
            'charset'   => $_ENV['FAMILY_V2_DB_CHARSET'],                //数据库字>符集

        ),
    ),

    /**
     * 自定义路由表
     */
    'tables' => array(
        //通用路由
        '__default__' => array(
            'prefix' => 'fami_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_A'),
            ),
        ),

        /**
        'demo' => array(                                                //表名
            'prefix' => 'tbl_',                                         //表名前缀
            'key' => 'id',                                              //表主键名
            'map' => array(                                             //表路由配置
                array('db' => 'db_demo'),                               //单表配置：array('db' => 服务器标记)
                array('start' => 0, 'end' => 2, 'db' => 'db_demo'),     //分表配置：array('start' => 开始下标, 'end' => 结束下标, 'db' => 服务器标记)
            ),
        ),
         */

        // 评论表 － 100张分表
        'feed_comments' => array(
            'prefix' => 'fami_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_A'),
                array('start' => 0, 'end' => 99, 'db' => 'db_A'),
            ),
        ),
    ),
);
