# 第5章 全新的创业项目

从这一章起，我们将来学习实践的项目开发。第一个项目案例是全新的创业项目。

## 5.1 项目背景

这是一个全新的创业项目，意味着开发团队要从0到1，搭建一套接口服务。该创业项目的名称叫WeTime，主要专注于精准订阅的社交日历。借助于PhalApi开发框架，开发团队需要交付一套满足业务需求的接口服务，提供给安卓版App、iOS版App和PC版管理后台调用，保证顺利完成产品的快速开发和迭代。最终上线的产品，期望首页运行效果请见图5-1。  

![图5-1 WeTime项目最终产品的首页](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-ios-p1.jpg)

图5-1 WeTime项目最终产品的首页

下面这一章，将讲解在WeTime项目中，如何快速开发接口服务，提供给各客户端产品使用。WeTime是一个真实的创业项目，实际上它所涵盖的技术面非常广，并且最初设计的系统的目标是能存储和处理大数据，能应对海量的访问。可以说，实现WeTime的接口服务系统有一定的技术难度，但在这里，为了方便初级开发工程师学习，我们特意将精简为迷你版的小型项目，并假设是5-8人的开发团队，采用的是非正式开发流程。

## 5.2 如何开启一个新项目

在启动一个新项目之前，需要完成一些前期的准备工作。对于普通的项目来说，通常会有需求分析、模块拆分、数据库设计、创建代码仓库、部分开发环境。然后，才可以进行具体的接口服务开发。下面将来分别介绍这些前期的准备工作。

### 5.2.1 需求分析与模块拆分

在前面有提到，WeTime项目主要专注于精准订阅的社交日历。但这一句话，对于理解项目的实际项目业务没有太大作用，这只能算是对产品功能的高度概括，或者说是产品的使命和愿景。而对于项目开发而言，对于开发工程师而言，只有充分理解需求了，才能更好地展开编程工作。  

如同一般性的项目开发，在项目最初，我们应该进行需求分析，深入理解WeTime这个产品到底具体是做什么的，它主要服务于哪些人群，提供哪些数据，需要进行哪些交互，以及它有哪些业务功能。在这里，WeTime的产品经理很好地回答了这些问题，并提供了一张非常具有指导价值的模块说明图。  

![图5-2 WeTime项目需求中的功能模块](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-wetime-func.jpg)

图5-2 WeTime项目需求中的功能模块

根据图5-2，WeTime产品的核心功能之一是提供基于日历的事件和动态，传播在社交网络，并通过用户订阅关注的方式实现精准推送，从而打造一款精准订阅的社交日历。到了后期，如果条件成熟，会进行平台与平台间的合作，结合新硬件（如iWatch）打造物联网实时的推送网络。除了社交日历这个基本的核心功能外，还有线下互动的活动邀请、提供给第三方接入的开发平台以及多维度的大数据分析等。  

在大致初步了解了产品需求后，接下来应该对产品的功能模块进行划分，以便明确模块之间的依赖关系，提前对项目进行概要设计。请注意，在这个项目的开发过程中，开发团队遵循的是敏捷开发流程，但敏捷开发流程并不意味着不需要进行设计。根据前面的需求分析，WeTime产品的功能众多，由于边幅有限，这一章，我们将重点讲解如何开发基本核心功能——社交日历模块的接口服务。 

下面是对将要开发的接口服务，按功能模块划分的情况。  

#### 基本的用户模块

首先，最容易想到的，就是几乎全部系统都会需要用到的基本功能模块——用户模块。在WeTime项目中，同样需要用户模块，并且这是核心功能模块所依赖的模块，是前置功能模板。也就是说，缺少用户模块，将难以开发后续的业务功能模块，尤其是在社交应用系统中。在项目初期，所需要的用户模块接口服务主要有两个：注册与登录。    

 + **注册账号** 进行账号注册，注册须提供账号、密码、昵称、头像图片等。  
 + **账号登录** 根据已注册的账号和密码进行登录。  
 
有了基本用户模块的接口服务后，接下来就是辅助的关注模块。
 
#### 辅助的关注模块

在虚拟的社交网络中，用户会对感兴趣的其他用户进行关注，或对不再感兴趣的用户取消关注。此时，需要用的则是辅助的关注模块。它主要的接口服务也只有两个：关注/取消关注、获取关注列表。  

 + **关注/取消关注**  对感兴趣用户进行关注，或对已关注的用户取消关注。  
 + **获取关注列表** 获取用户已关注的用户列表。  

构建完社交网络所需求的接口服务后，下一步，便可以开发核心的功能模块——社交日历模块了。

#### 核心的日历事件模块

经过进一步分解，社交日历模块主要涉及的接口服务有： 发布事件、查看事件和操作事件这三个。   

 + **发布日历事件** 用户发布一个新事件到社交日历，事件的信息主要有标题、内容、地理位置、权限（公开或私有）、有效时间、图片素材等。  
 + **查看日历事件列表** 用户可以查看自己以及所关注/订阅的好友所发布的日历事件。  
 + **操作日历事件** 用户可以对已发布的日历事件进行操作，主要有删除事件、把事件标识为已完成或未完成。  

虽然上面共有7个接口服务，但理解起来并不难。我们可以通过一个模拟的业务场景来加深对这些接口服务的应用。下面是一个连贯使用了上述7个接口服务的模拟故事场景。  

Aevit是一位资深的iOS开发工程师，同时也是一位喜欢途游的户外爱好者，他将打算用半个月探索西藏神秘的自然风光。在出发前，他来到了WeTime，希望通过这个平台与更多志同道合的旅友一起分享他的经历。  

 + 1、Aevit打开WeTime，并注册了一个账号（注册账号）；
 + 2、Aevit使用刚注册的账号成功登录了WeTime（账号登录）；
 + 3、Aevit发现了一位有趣的旅友Angle，并关注了她（关注/取消关注）；
 + 4、在欣赏西藏盐田美妙风景的同时，Aevit发布了下一步的计划动态（发布日历事件）；
 + 5、坐在前往下一站的车上，Aevit看到了自己和Angle已发布的动态，愉快地消遣在途中的时间（查看日历事件列表）；
 + 6、到站后，Aevit按之前发布的事件进行了相关的准备，并将事件置为已完成（操作日历事件）；
 + 7、突然想到之前关注的Angle也来到了这个站，Aevit赶紧打开WeTime，查看了自己的关注列表（获取关注列表）……；

这是一模拟的故事，但对于我们将要开发的接口服务，有着很好的启发性。  

### 5.2.2 数据库设计

WeTime项目使用的是MySQL数据库，我们先简单了解一下WeTime系统数据库的设计，以及各数据库表的设计。  

![图5-3 WeTime项目初期的ER图设计](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-db-er.png)

图5-3 WeTime项目初期的ER图设计

根据图5-3，在项目初期根据需求抽象的逻辑关系是，用户与用户之间可以相互关注，并且一个用户可以发布多条事件。其他逻辑关系先暂不关注，因为我们本章重点关系用户实体与事件实体之间的关系。  

数据库名则以项目名来命名，并叫做：wetime，数据库表约定使用统一表前缀“wt_”，表示WeTime的缩写。结合前面的需求分析，功能模块划分，以及数据库的设计，我们目前共需要三张数据库表，分别是：用户表、关注表和事件表。  

先创建一个wetime数据库，可以使用：  
```sql
CREATE DATABASE `wetime`;
```

#### 用户表

用户表记录了用户的基本账号信息，其数据库表名为：wt_user，表结构如下：  

表5-1 用户表wt_user的表结构

字段|类型|允许空值|索引|注释
---|---|---|---|---
id|int(10) unsigned|否|主键|UID
username|char(20)|否||用户名
nickname|char(20)|否||昵称
password|char(32)|否||密码
salt|varchar(32)|是||随机加密因子
avatar|varchar(255)|否||头像
regtime|datetime|否||注册时间

对应的数据库建表SQL为：  
```sql
CREATE TABLE `wt_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(32) DEFAULT NULL COMMENT '随机加密因子',
   `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `regtime` int(11) DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

#### 关注映射表

关注映射表纪录了用户与用户之间的关注关系，考虑到后续需求需要对关注的用户进行分组，以及按订阅的分组进行精准推送，这里需要额外添加订阅分组ID。关注映射表的数据库表名为：wt_follow，它的表结构如下：  

表5-2 关注映射表wt_follow的表结构

字段|类型|允许空值|索引|注释
---|---|---|---|---
id|int(10) unsigned|否|主键|关联ID
gid|int(10) unsigned|否||订阅分组ID
uid|int(10) unsigned|否||属于订阅分组的用户UID
touid|int(10) unsigned|否||订阅分组所属的用户UID
createtime|datetime|否||用户添加到订阅分组的时间

对应的数据库建表SQL为：  
```sql
CREATE TABLE `wt_follow` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT COMMENT '关联ID',
  `gid` int(10) unsigned NOT NULL COMMENT '订阅分组ID',
  `uid` int(10) unsigned NOT NULL COMMENT '属于订阅分组的用户UID',
  `touid` int(10) unsigned NOT NULL COMMENT '订阅分组所属的用户UID',
  `createtime` datetime NOT NULL COMMENT '用户添加到订阅分组的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

#### 日历事件表

还有一个关键的数据库表是日历事件表，用于存放用户所发布的日历事件的标题、内容等信息。日历事件表的数据库表名为：wt_event，对应的表结构为：  

表5-3 日历事件表wt_event

字段|类型|允许空值|索引|注释
---|---|---|---|---
id|int(10) unsigned|否|主键|事件ID
uid|int(10) unsigned|否||发布者UID
title|char(50)|否||标题
content|char(200)|否||内容
location|char(50)|是||位置信息
createtime|datetime|否||发布时间
state|enum('0','1','2')|否||状态（0：已删除；1：未完成；2：已完成）
tousers|enum('0','1','2')|否||事件的权限（0：私有；1：公开；2：共享）
  
对应的数据库建表SQL为：  
```sql
CREATE TABLE `wt_event` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '事件ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布者UID',
  `title` char(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` char(200) NOT NULL DEFAULT '' COMMENT '内容',
  `location` char(50) DEFAULT '' COMMENT '位置信息（待定）',
  `createtime` datetime NOT NULL COMMENT '发布时间',
  `state` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '状态（0：已删除；1：未完成；2：已完成）',
  `tousers` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '事件的权限（0：私有；1：公开；2：共享）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

至少为此，我们创建了wetime数据库，并共创建了三张数据库表，分别是用户表wt_user，关注映射表wt_follow，和日历事件表wt_event。这些只是部分的数据库表，希望这三个简单的表没有让你觉得混乱。同时需要注意的是，这三张表的结构并不是最终版的，在开发迭代过程中，会根据需求不断进行调整或扩展。但目前这些表结构已经可以很好的满足我们本次项目的开发要求了。  

接下来，我们再来说一下项目代码。  

### 5.2.3 为项目创建Git代码仓库

对于项目的代码，你可以选择任何一款CSV进行代码的版本管理，这里约定使用的是Git。为此，需要为我们的项目创建一个单独的Git仓库，并将PhalApi框架的最新版本的代码从Github/码云上的远程仓库下载到本地，然后导入到新建的仓库中。这里使用PhalApi的版本是 1.4.0版本，并且创建的Git仓库名字为WeTime。以下命令演示了这一操作过程。   

先将PhalApi最新版的代码签出到本地，如这里的：  
```bash
$ git clone https://github.com/phalapi/phalapi.git
```
然后，创建一个WeTime目录，进行Git初始化，并把上面签出的PhalApi框架代码拷贝到项目目录下。  
```bash
$ mkdir WeTime
$ cd WeTime
$ git init
$ cp /path/to/PhalApi/* ./ -R
```
接着，进行Git的提交和创建项目操作。  
```
$ git add .
$ git commit -a -m "第一次提交，使用PhalApi框架1.4.0版本"
```
最后，根据存放Git项目的情况相应进行推送。可以是保存在本地，可以是保存在内部的服务器，也可以是保存在Github或者其他托管平台上。请注意，如果是使用第三方托管平台，请将项目设置为私有，进行代码的权限控制。这里出于教学的原因，并没有专门创建一个新的项目，而是把WeTime整个项目的关键源代码保存在了本书指定的Git项目下，方便读者查阅。

如果你的团队使用的是SVN，也可以相应进行创建，这里不再赘述。  

### 5.2.4 部署开发环境

准备好数据库和代码仓库后，下一步就可以部署搭建开发环境，以便随时进行具体的接口服务开发了。出于教学目的，这次讲解WeTime项目开发的过程中，使用的开发环境也是本书所统一约定的环境，即：  

 + PHP 5.3.10
 + Nginx 1.1.19
 + PhalApi 1.4.0
 + Ubuntu 12.04（64位）

假设WeTime项目系统的最终域名为：api.wetime.com，故在部署开发环境时，我们也使用同样的域名。以下是本次的nginx配置，保存在文件/etc/nginx/sites-available/api.wetime.com中，可作为参考。  
```
server {
    root /path/to/meet/src/WeTime/Public;
    index index.php;

    server_name  api.wetime.com;

    location / {
        try_files $uri $uri/ /index.php;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass 127.0.0.1:9000;
        include fastcgi_params;
    }

    error_log /var/log/nginx/api.wetime.com.error_log;
    access_log /var/log/nginx/api.wetime.com.access_log;
}
```
在Ubuntu下，需要添加软链到sites-enabled目录才可使nginx配置生效，如这里的：  
```bash
# ln -s /etc/nginx/sites-available/api.wetime.com /etc/nginx/sites-enabled/api.wetime.com
```
配置好后，重启nginx。  
```bash
# service nginx restart
```
最后，在/etc/hosts文件添加host便可访问默认接口服务，测试配置是否正确。  
```bash
127.0.0.1 api.wetime.com
```
如果打开浏览器，访问：http://api.wetime.com/demo/，能看到默认接口的结果返回，并表明开发环境已部署成功。在这基础上，采用自己喜欢的方式，为我们的WeTime项目创建一个接口服务项目，如这里取名为Fun。创建成功后，实现的源代码放在./Fun目录下，而对外访问的入口是./Public/fun目录。例如，访问http://api.wetime.com/fun/，可以看到：  
```
{
    "ret": 200,
    "data": {
        "title": "Hello World!",
        "content": "PHPer您好，欢迎使用PhalApi！",
        "version": "1.4.0",
        "time": 1495984417
    },
    "msg": ""
}
```

关于数据库，假设已搭建好数据库服务器环境，并已经创建上述的数据库和数据库表，以及相关的数据库连接已更新到./Config/dbs.php配置文件。  

## 5.3 具体接口服务开发

接下来，我们将来讲解如何进行具体的接口服务开发。

### 5.3.1 制定接口规范

为方便客户端接入访问接口服务，制定明确统一的接口规范是很有必要的。  

#### 接口系统域名
WeTime接口系统域名是：  ```api.wetime.com```，绑定不同环境的host，即可访问对应服务环境的接口服务。  

#### 接口访问方式与返回格式
和PhalApi框架提供的默认访问一样，WeTime使用的也是HTTP/HTTPS访问方式，并以JSON格式返回。  

#### 接口签名方案
使用的是PhalApi默认提供的签名验证方案，即简单的MD5验证。简单回顾一下PhalApi_Filter_SimpleMD5的验签算法：  

 + 1、排除签名参数（默认是sign）
 + 2、将剩下的全部参数，按参数名字进行字典排序
 + 3、将排序好的参数，全部用字符串拼接起来
 + 4、进行md5运算并比较

此时，需要开启MD5验签，对应修改的代码是：  
```php
// WeTime$ vim ./Public/init.php
DI()->filter = 'PhalApi_Filter_SimpleMD5';
```
#### 公共接口参数

提前约定好公共接口参数，对于日后定位和排查线上问题、进行接口服务的版本管理以及监控统计等都有很大的帮助。就WeTime项目而言，它的公共接口参数有：  

表5-4 WeTime项目的公共接口参数

公共接口参数字段|公共接口参数名|类型|是否必须|默认值|说明
---|---|---|---|---
service|接口服务名称|字符串|否，但通常情况下必须|```Default.Index```|待请求的接口服务名称
client|客户端类型|枚举类型|是|pc|客户端类型，值为：ios/android/pc，区分大小写
version|客户端版本号|字符串|否||客户端当前版本号，格式为X.X.X，如：1.0.1
user_id|用户ID|整型|否|0|只有当接口需要获取用户相关信息才需要提供
sign|签名|字符串|是||加密后的签名

对应的参数规则配置，维护在配置文件./Config/app.php里面的apiCommonRules选项中。如上面的公共接口参数，其规则对应是：  
```php
// WeTime$ vim ./Config/app.php 
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
```
除此之外，在WeTime项目中，约定公共接口参数以GET方式传递，具体接口服务的参数则采用POST方式传递。

### 5.3.2 日历事件模块的三个接口服务

PhalApi推荐使用测试驱动开发，因此在WeTime项目开发过程中，我们遵循了TDD这一最佳开发实践。如前面所述，本章中涉及开发的模块有基本的用户模块、辅助的关注模块、核心的日历事件模块。其中，用户模块和关注模块，是相对简单的功能模块，考虑到其实现简单以及篇幅有限，我们这里不对其进行讲解，而侧重讲解核心的日历事件模块的接口服务开发。   

日历事件模块目前有三个接口服务待开发，分别是：发布日历事件、查看日历事件列表和操作日历事件。  

发布日历事件是指用户发布一个新的事件到社交日历，用技术的表达方式是指为已登录的用户在数据库日历事件表wt_event中添加一条新的纪录数据。查看日历事件列表，是指用户自己或者其他用户可以查看到自己以及所关注/订阅的好友所发布的日历事件。最后，用户可以通过对自己已发布的日历事件进行操作，主要有删除事件、把事件标识为已完成或未完成。  

基于这样的需求理解，让我们来创建对应的接口类吧！这里的日历事件接口类为：Api_Event，上面三个接口服务对应的方法分别```Api_Event::post()```，```Api_Event::space()```和```Api_Event::operate()```。即：  
```php
// WeTime$ vim ./Fun/Api/Event.php
<?php
/**
 * 日历事件接口类
 */

class Api_Event extends PhalApi_Api {
 
    public function post() {
    }

    public function space() {
    }

    public function operate() {
    }
} 
```

至此，我们便有了这三个接口服务的雏形。虽然尚未指定接口所需要的参数，以及返回的结果格式，但也算是在接口类中定义了接口服务的函数签名。  

表5-5 日历事件模块的接口服务对照表
接口服务|service名称|对应的类方法|完整的访问路径
---|---|---|---
发布日历事件|Event.Post|Api_Event::post()|http://api.wetime.com/fun/?service=Event.Post
查看日历事件列表|Event.Space|Api_Event::space()|http://api.wetime.com/fun/?service=Event.Space
操作日历事件|Event.Operate|Api_Event::operate()|http://api.wetime.com/fun/?service=Event.Operate

接下来，看下如何在TDD的指导下，出色地完成这些接口服务的功能开发。

### 5.2.3 日历事件接口服务的开发

#### 生成测试骨架代码

创建好基本的日历事件接口类后，便可以使用phalapi-buildtest脚本命令生成测试骨架代码。如这里的：  
```bash
WeTime$ ./PhalApi/phalapi-buildtest ./Fun/Api/Event.php Api_Event ./Fun/Tests/test_env.php > ./Fun/Tests/Api/Api_Event_Test.php
```
生成后，进行相应的require引入调整。调整后，试运行一下单元测试。  
```bash
$ phpunit ./Fun/Tests/Api/Api_Event_Test.php 
PHPUnit 4.3.4 by Sebastian Bergmann.

...

Time: 7 ms, Memory: 6.00Mb

OK (3 tests, 0 assertions)
```

#### 完善接口类的测试用例，让测试失败

让我们先来看下发布日历事件这个接口服务，并在测试用例中为其制作一个Happy Path。也就是说，我们期望顺利地模拟用户成功发布一个日历事件。  

但在开始开发日历事件模块前，假设基本的用户模块和辅助的关注模块已开发完成，并假设我们已经有了Aevit和Angle这两位用户，其中Aevit的账号ID为1，而Angle的账号ID为2。  
```sql
INSERT INTO `wt_user` (`id`, `username`, `nickname`, `password`, `salt`, `regtime`, `avatar`) VALUES ('1', 'Aevit', 'Aevit', '09d58b30f2b967c80ae1094be664ac66', 'c2cb97f6c3', '0', '/images/aevit.jpg');
INSERT INTO `wt_user` (`id`, `username`, `nickname`, `password`, `salt`, `regtime`, `avatar`)  VALUES ('2', 'Angle', 'Angle', 'a01fb02627c0206ab2d0a928729e9410', 'd41028d9b0', '0', '/images/angle.jpg');
```

若要成功地发布一个日历事件，则需要提供全部必须的有效数据。以下是模拟Aevit发布事件的测试场景。  
```php
// WeTime$ vim ./Fun/Tests/Api/Api_Event_Test.php
    public function testPost()
    {
        // Step 1. 构建
        $url = 'service=Event.Post&client=ios&version=1.0.1&user_id=1&sign=9793325c851346a6af041ce5a1e69476';
        $params = array(
            'title' => '测试事件',
            'content' => '这是一个测试事件',
            'tousers' => '1',
        );
        
        // Step 2. 执行
        $rs = PhalApi_Helper_TestRunner::go($url, $params);
        
        // Step 3. 验证
        $this->assertGreaterThan(0, $rs['id']);
    }
```
在上面测试用例中，模拟用户ID为1，即Aevit用户，发布一个日历事件，并期望发布后返回的事件ID大于0，表示新增数据库表纪录成功后返回的自增ID应大于0。这时，运行单元测试，可以看到这时是失败的。  
```bash
WeTime$ phpunit ./Fun/Tests/Api/Api_Event_Test.php
... ...
1) PhpUnderControl_ApiEvent_Test::testPost
Failed asserting that null is greater than 0.
... ...
```

#### 在意图导向下完成具体功能开发，让测试通过

在明确了接口服务的需求，并且有失败的单元测试作为指明，要实现此日历事件发布功能就很简单了。关键的是如何把合理地组织代码，把代码放在最合适的位置上。  

首先，先在接口类Api_Event中补充发布日历事件所需要的参数的规则配置。这时，可结合前面日历事件数据库表wt_event的表结构进行相应的配置，只需要稍微转换一下即可。  

```php
// WeTime$ vim ./Fun/Api/Event.php
class Api_Event extends PhalApi_Api {

    public function getRules() {
        return array(
            'post' => array(
                'title' => array('name' => 'title', 'min' => 1, 'max' => 50, 'require' => true, 'desc' => '标题'),
                'content' => array('name' => 'content', 'min' => 1, 'max' => 200, 'require' => true, 'desc' => '内容'),
                'location' => array('name' => 'location', 'max' => 50, 'desc' => '位
置信息'),
                'createTime' => array('name' => 'createtime', 'type' => 'date', 'desc' => '发布时间'),
                'tousers' => array('name' => 'tousers', 'type' => 'enum', 'range' => array('0', '1', '2'), 'default' => '1', 'desc' => '事件的权限（0：私有；1：公开；2：共享）'),
            ),
        );
    }
    ... ...
```

接着，实现接口类的调用功能。Api_Event接口类在发布日历事件时，主要需要进行的工作有：检测用户是否已登录，收集验证通过和解析的事件信息，最后调用日历事件领域类进行发布。以下是对应的实现代码。  
```php
// WeTime$ vim ./Fun/Api/Event.php
    public function post() {
        if ($this->userId <= 0) {
            throw new PhalApi_Exception_InternalServerError('用户未登录');
        }

        $newEvent = array(
            'uid'           => $this->userId,
            'title'         => $this->title,
            'content'       => $this->content,
            'location'      => $this->location,
            'createtime'    => $this->createTime,
            'tousers'       => $this->tousers,
        );

        $domain = new Domain_Event();
        $id = $domain->post($newEvent);

        return array('id' => $id);
    }
```
上面有一个初学者很容易犯的错误，就是在需要进行检测用户登录态的场景中，并没有严格进行检测。如上面只是简单地判断用户ID是否大于0，而没有验证其真实性，更好的做法是进行会话判断。关于会话验证这块，暂时不做过多的介绍，以免分散日历事件模块的开发。  

再次执行单元测试，会提示类Domain_Event未找到，这意味着要补充日历事件领域业务类。此领域业务类主要是完善日历事件的发面信息，如补充默认的状态值和发布时间，让事件信息更齐全。最终会调用数据模型类进行具体的数据持久化操作。  

```php
// WeTime$ vim ./Fun/Domain/Event.php
<?php
/**
 * 日历事件领域业务类
 */

class Domain_Event {

    public function post($newEvent) {
        $newEvent['state'] = '1';
        if (empty($newEvent['createtime'])) {
            $newEvent['createtime'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        }

        $model = new Model_Event();
        $id = $model->insert($newEvent);

        return $id;
    }
}
```
请注意，在实现接口服务时，为了更快速完成代码的编写，初学者更容易产生一些临时、相对不符合规范的代码。例如这里对于state字段，使用了魔法字符串“'1'”（之所以是字符串类型，是因为wt_event表的state字段为枚举类型，不能用整型），虽然暂时知道1表示事件未完成，即激活状态，但一段时间后其他开发人员甚至原作者再来阅读这行代码时，就难以判断这个1为何物了。但继续功能开发，关于代码的重构，后面会专门讲到。  

再次执行单元测试，会提示类Model_Event未找到。此时，可再添加与日历事件表wt_event的数据业务类。这个类暂时的实现很简单，只需要继承于PhalApi_Model_NotORM父类即可。  
```php
// WeTime$ vim ./Fun/Model/Event.php
<?php
/**
 * 日历事件数据模型类
 */
class Model_Event extends PhalApi_Model_NotORM {
 
} 
```

添加Model类后，如果提示数据库连接失败，则应该检测数据库配置文件./Config/dbs.php的连接信息是否正确。如果提示数据库表不存在，则应该检测数据库表前缀是否设置为“wt_”，以及数据库表wt_event是否已创建。 

```php
// WeTime$ vim ./Config/dbs.php
return array(
    'servers' => array(
        'db_wetime' => array(                         //调整服务器标记
            'host'      => 'localhost', 
            'name'      => 'wetime',                  //数据库名字为“wetime”
            'user'      => 'root', 
            'password'  => '',    
            'port'      => '3306',    
            'charset'   => 'UTF8',               
        ),
    ),

    'tables' => array(
        '__default__' => array(
            'prefix' => 'wt_',                        //统一表前缀为“wt_”
            'key' => 'id',
            'map' => array(
                array('db' => 'db_wetime'),           //更改为对应的服务器标记
            ),
        ),
    ),
);
```
到目前为止，在单元测试的指引下，我们完成了发布日历事件最小化的代码开发。这时再次执行单元测试，会发现已经通过了！  
```bash
WeTime$ phpunit ./Fun/Tests/Api/Api_Event_Test.php 
... ...
OK (3 tests, 1 assertion)
```
访问数据库，可以在wt_event表中看到刚成功发布的事件信息。  

表5-6 通过单元测试新增的日历事件数据

id|uid|title|content|location|createtime|state|tousers
---|---|---|---|---|---|---|---
1|	1|	测试事件|	这是一个测试事件|		|2017-05-29 09:29:07|	1|	1

就这样，我们第一个接口服务就已经初步开发完成了！

#### 进行适当的重构，追求更高的代码质量

前面讲到，在Domain_Event类中存在一个魔法字符串，相关的代码片段是：  
```php
$newEvent['state'] = '1';
```
要重构这行代码很简单，只需用常量来标识对应的state表字段的枚举值即可。重构后的代码片段如下所示：   
```php
// WeTime$ vim ./Fun/Domain/Event.php
class Domain_Event {

    // 0：已删除；1：未完成；2：已完成
    const STATE_DELETED = '0';
    const STATE_ACTIVE  = '1';
    const STATE_DONE    = '2';

    public function post($newEvent) {
        $newEvent['state'] = self::STATE_ACTIVE;
        ... ...
```

每次重构后，执行一次单元测试，确保原来的功能不受影响。

#### 为领域业务类和数据模型类补充对应的测试代码
 
日历事件领域业务类Domain_Event的逻辑规则非常简单，使用phalapi-buildtest创建测试骨架后参考上面的测试用例场景补充相应的测试用例即可。而对于数据模型类Model_Event，则更为简单，因为它只是单纯继承父类，没有其他的实现代码，所以可暂时不补充其单元测试。

#### 执行单元测试套件，确保全部测试通过，没有引入新的问题
 
 
 由于是首次开发接口服务，原来还没有任何接口服务代码，因此也就不存在旧的单元测试。但这时也依然可以执行一下全部的单元测试，以便发现有没其他的问题还待解决。结果发现，添加了必须的sign接口参数后，会导致默认的接口服务在测试时出现“缺少必要参数sign”的异常。根据错误提示信息，调整相应测试用例的场景数据即可。  
 
### 5.3.4 查看日历事件列表接口服务的开发

日历事件发布接口不具备幂等性，因为每次成功调用它都会发布一个新的日历事件，在数据库表中添加一条新的数据纪录。而将要开发的查看日历事件列表接口，则相对具有幂等性。有没有日历事件更新的前提下，每次所查看到的事件列表应该是一样的。很明显，在这个接口服务中，需要根据一定的条件和规则进行筛选，返回满足要求的事件列表，并需要支持分页操作。遵循TDD下的开发步骤，可以如法炮制快速开发此查看日历事件列表接口服务。由于开发过程类似，下面将简明介绍，省略重复部分的讲解。  

同样，在开始开发查看日历事件列表接口服务前，有一些前置条件。假设Aevit已关注了用户Angle，并且这两位用户在一段很长的时间内均已发布了大量的日历事件。在关注数据库中，下面这条纪录表示Aevit（用户ID为1）关注了Angle（用户ID为2）。  
```sql
INSERT INTO `wt_follow` (`id`, `gid`, `uid`, `touid`, `createtime`) VALUES ('1', '0', '1', '2', '2017-05-29 10:55:01');
```

首先，对查看日历事件列表，补充完善的测试用例，使之测试失败。通过事先编写单元测试，有助于迫使我们一开始就关注高层的概念和业务需求，而非一头就扎进实现的细节和纠结于使用何种技术。    
```php
    public function testSpace()
    {
        // Step 1. 构建
        $url = 'service=Event.Space&client=ios&version=1.0.1&user_id=1&sign=';
        $params = array(
            'perpage' => 5,
            'page' => 1,
            'createtime' => '2017-05-29 59:59:59',
        );

        // Step 2. 执行
        $rs = PhalApi_Helper_TestRunner::go($url, $params);

        // Step 3. 验证
        $this->assertGreaterThan(0, $rs['total']);
        $this->assertEquals(5, $rs['perpage']);
        $this->assertEquals(1, $rs['page']);

        $this->assertNotEmpty($rs['list']);
        $this->assertLessThanOrEqual(5, count($rs['list']));
        
        foreach ($rs['list'] as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('uid', $item);
            $this->assertArrayHasKey('user', $item);
            $this->assertArrayHasKey('title', $item);
            $this->assertArrayHasKey('content', $item);
            $this->assertArrayHasKey('createtime', $item);

            $this->assertArrayHasKey('avatar', $item['user']); //用户头像
        }

        $allUid = array();
        foreach ($rs['list'] as $item) {
            $allUid[] = $item['uid'];
        }
        $this->assertContains('1', $allUid); //1为Aevit
        $this->assertContains('2', $allUid); //2为Angle
    }
```
此时，为了只执行这个测试用例，而不执行前面发布日历事件的测试用例，可以使用phpunit的```--filter```参数，即：  
```bash
WeTime$ phpunit --filter testSpace ./Fun/Tests/Api/Api_Event_Test.php 
```
还记得吗？我们在WeTime项目中开启了简单的MD5签名验证服务，因此会提示“签名错误”这样的异常。为了解决在单元测试时签名验证这个问题，可以有两种方案，一种是针对每次测试用例中根据控制台的日记信息，把正确的签名手动更新到测试用例中的sign参数。例如在控制台看到：  
```
2017-05-29 10:41:00|DEBUG|Wrong Sign|{"needSign":"9897a2670cc329ce8c49a65118ff7287"}
```
即可把“9897a2670cc329ce8c49a65118ff7287”更新到sign参数。  
```php
        $url = 'service=Event.Space&client=ios&version=1.0.1&user_id=1&sign=9897a2670cc329ce8c49a65118ff7287';
```

再来分析下这里的测试用例最后的断言部分。查看接口与前面的发布接口不一样，显然里面的业务规则更为复杂，牵涉的数据更广。断言部分表明了最终客户端需要哪些业务数据，而这些数据包括：  

 + 列表分页的相关数据，如总数量total，透传返回的分页数量perpage和当前是第几页；
 + 在list字段中返回列表的精制数据；
 + 在列表的条目中，返回日历事件的相关数据，包括事件ID、发布者的用户ID、事件标题和内容以及发布的时间；
 + 在列表条目中附上发布者对应的用户信息，例如用户头像；
 + 最后的断言，确保用户既可以看到自己发布的事件，还可以看到所关注的用户发布的事件。

接下来，以失败的单元测试作为指导，继续我们的编码开发。

先来看下需要配置哪些接口参数，主要有用于分页显示的每页数量perpage和表示当前第几页的page，以及用于用于过滤发布时间的createtime。结合前面的**2.1.2 参数规则**，不能得出下面这样的参数规则配置。 
```php
// WeTime$ vim ./Fun/Api/Event.php
    public function getRules() {
        return array(
            'post' => array(
                ... ...
            ),
            'space' => array(
                'perpage' => array('name' => 'perpage', 'type' => 'int', 'default' => 20, 'min' => 1, 'max' => 100, 'desc' => '分页数量'),
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'min' => 1, 'desc' => '当前第几页'),
                'createTime' => array('name' => 'createtime', 'type' => 'date', 'desc' => '发布时间'),
            ),
        );
    }
```
查看日历事件列表的接口服务，返回的数据可以分为两组，一组是用于协助客户端进行分页的数据，另一组是事件列表的业务数据。在接口类开发时，可以将这两组数据的获取分别进行实现。如同前面的发布接口，这里也使用了不严格的登录态检测。  
```php
// WeTime$ vim ./Fun/Api/Event.php
    public function space() {
        if ($this->userId <= 0) {
            throw new Phalapi_Exception_InternalServerError('用户未登录');
        }

        $domain = new Domain_Event();
        $total = $domain->getSpaceTotal($this->userId, $this->createTime);

        $list = $domain->getSpaceList($this->userId, $this->createTime, $this->perpage, $this->page);

        return array(
            'total' => $total,
            'perpage' => $this->perpage,
            'page' => $this->page,
            'list' => $list
        );
    }
```
通过日历事件领域业务类的```Domain_Event::getSpaceTotal($userId, $createTime)```类方法可以获取列表的总数量，这时不需要分页参数。而通过```Domain_Event::getSpaceList($userId, $createTime, $perpage = 20, $page = 1)```类方法获取事件列表时则需要在后面添加分页参数。编写好接口层的实现后，运行一下单元测试，会提示调用了未定义的类方法```Domain_Event::getSpaceTotal()```。由于这种开发模式更倾向于深度优先的实现，因此我们先来实现获取列表总数量，再实现获取事件列表。  

根据失败的提示，需要在Domain_Event类中补充对应方法的实现。考虑到要能同时查看用户自己和用户所关注的好友的事件，所以我们需要先取到用户关注了哪些其他用户。这将涉及到与事件获取不同的另一块业务规则，根据亲密性，这块功能的实现应该通过委托交由关注领域业务类来负责。因此就有了下面这样的代码：  
```php
// WeTime$ vim ./Fun/Domain/Event.php
    public function getSpaceTotal($userId, $createTime) {
        $domainFollow = new Domain_Follow();
        $followUids = $domainFollow->getFollowUids($userId);
    }        
```
这里发生一段小插曲，在开发查看日历事件列表这一主要功能的过程中，我们调用了尚未存在功能——获取用户关注的用户ID列表。为了解决这个问题，需要根据目前已有的数据库表结构、业务需求以及当前上下文场景需要的数据，填补这一空缺。好吧，原来我们走的是一条笔直的大道，现在要走一条小道了……  

使用我们喜欢的编辑器，创建上面需要的关注领域业务类Domain_Follow。然后添加获取关注用户ID列表的方法，并调用对应的数据模型类，完成具体的数据获取。  
```php
// WeTime$ vim ./Fun/Domain/Follow.php
<?php
/**
 * 关注领域业务类
 */
class Domain_Follow {
 
    public function getFollowUids($userId) {
        $model = new Model_Follow();
        return $model->getFollowUids($userId);
    }
} 
```
或许已经有读者开始觉得厌烦了，因为我又要重复强调需要执行单元测试了。但是作为专业的开发工程师，应该有意识地进行这些实践。即每一次添加一段代码或者修改一段代码后，都应该执行一下单元测试。如果这时执行单元测试，你会发现有这样的错误提示：“PHP Fatal error:  Class 'Model_Follow' not found in……”。此时此刻，从一开始获取事件列表，到现在提示类Model_Follow未找到，仿佛我们已渐行渐远，仿佛我们已失去了最初的关注点。尤其如果这时，正当我们还在思考下一步要做什么时，被外界打断了，例如被产品经理拉去开了长达1小时的会议，又或者是刚好下班了。当再回到屏幕前，我们还能记得明确需要做什么吗？常问路的人不会迷路。如果觉得迷茫或者没有方向时，可以来问一下单元测试，它会告诉你方向。  

回到主题，既然没有Model_Follow这个类，很简单，添加一个便是了。结合**2.5.4 CURD基本操作**，不能得出根据一定条件获取全部数据纪录的实现代码。  
```php
WeTime$ vim ./Fun/Model/Follow.php

<?php
/**
 * 关注数据模型类
 */
class Model_Follow extends PhalApi_Model_NotORM {

    public function getFollowUids($userId) {
        $rows = $this->getORM()
            ->select('touid')
            ->where('uid', $userId)
            ->fetchAll();

        $uids = array();
        foreach ($rows as $row) {
            $uids[] = intval($row['touid']);
        }

        return $uids;
    }
}
```
在上面代码中，先根据用户ID获取全部关注的用户ID，然后再处理返回的数据库结果集，将每条纪录中的touid提取出来，最后返回给调用方。  

在开发过程中，有没发现，每一段代码，都是非常简明扼要的。没有多余的一行代码，基本上每一行代码都在发挥着不可或缺的作用。一行代码也不能少，一行代码也不能多。实现了用户所关注的用户ID列表功能后，我们相当于完成了一个“子程序”的功能开发。在以后需要用到相同的业务数据时，可以重用此“子程序”。可以说，我们取得了阶段性的成果，并产出的是灵活、职责划分明确、优雅而可重用的代码。  

分支这条小道，我们就先暂告一段落。再一次，通过单元测试，可以清晰地知道，当前我们所在的处境。  
```bash
1) PhpUnderControl_ApiEvent_Test::testSpace
Failed asserting that null is greater than 0.
```
这里的错误表明，我们还需要继续完成对获取事件列表总数这一功能的实现。前面通过一条小道拿到了用户关注的用户ID列表这份“魔法配料”后，让我们回到最初的出发地——日历事件领域业务类Domain_Event。把当前用户的ID，和所关注的用户ID列表，可以得到全部待获取事件的用户ID。  
```php
        $allUids = array_merge($followUids, array($userId));
```
有了这些充分的参数后，便不能通过日历事件数据模型类Model_Event从数据库中获取满足条件的总数了。至此，·```Domain_Event::getSpaceTotal($userId, $createTime)```对应的代码是：    
```php
// WeTime$ vim ./Fun/Domain/Event.php
    public function getSpaceTotal($userId, $createTime) {
        $domainFollow = new Domain_Follow();
        $followUids = $domainFollow->getFollowUids($userId);
        $allUids = array_merge($followUids, array($userId));

        $model = new Model_Event();
        return $model->getSpaceTotal($allUids, $createTime);
    }
```
随后，执行一下单元测试，并在Model_Event类中实现```getSpaceTotal($allUids, $createTime)```这一方法。
```php
// WeTime$ vim ./Fun/Model/Event.php 
<?php
/**
 * 日历事件数据模型类
 */
class Model_Event extends PhalApi_Model_NotORM {

    public function getSpaceTotal($allUids, $createTime) {
        $total = $this->getORM()
            ->where('uid', $allUids)
            ->where('createtime < ?', $createTime)
            ->where('tousers', '1')
            ->count('id');
        return intval($total);
    }
}
```
上面代码中，使用了```where('tousers', '1')```，是因为默认获取的事件列表应该是公开的。为了查看对应的数据库查询语句，可以在单元测试环境下开启调试模式。如这里，开启后，可以看到上面的查询对应的SQL语句是：  
```sql
SELECT COUNT(id) FROM wt_event WHERE (uid IN (2, 1)) AND (createtime < ?) AND (tousers = '1'); -- '2017-05-29 59:59:59'
```

到这里，我们再一次完成了阶段性的成果！如前面所述，获取日历事件列表主要有两组数据，分页数据和列表数据。通过单元测试的验证，第一组数据已经通过测试了。这意味着分页数据已经开发完成了！”革命尚未成功，同志仍需继续努力！“接下来，继续完成第二组数据的开发。  

列表数据是通过```Domain_Event::getSpaceList($userId, $createTime, $perpage = 20, $page = 1)```类方法来提供的，参考前面的实现，不难得出它的实现代码。  
```php
// WeTime$ vim ./Fun/Domain/Event.php 
    public function getSpaceList($userId, $createTime, $perpage = 20, $page = 1) {
        $domainFollow = new Domain_Follow();
        $followUids = $domainFollow->getFollowUids($userId);
        $allUids = array_merge($followUids, array($userId));

        $model = new Model_Event();
        return $model->getSpaceList($allUids, $createTime, $perpage, $page);
    }
```
有时，参考即暗示着“复制-粘贴式编程”，这样很容易催生重复的代码。例如在上面代码中，前面的参数准备是和获取总数时的准备参数是一样的。但关于如何消除重复代码这一异味，后面在重构环节会处理。为了不扰乱我们开发的思路，暂且“绕过”这个问题，但后面我们一定会回来专门处理它。有代码洁癖的程序员是不会容许任何重复的代码的。  

在添加并实现数据模型类Model_Event的类方法前，细心的读者可以发现，在前面调用的代码中，其实已经很好地给出了待实现的类方法的函数签名。这一细节，也很好地表明了为什么在意图导向编程下能更好的提高关注点。  
```php
// WeTime$ vim ./Fun/Model/Event.php
    public function getSpaceList($allUids, $createTime, $perpage, $page) {
        return $this->getORM()
            ->select('id, uid, title, content, createtime')
            ->where('uid', $allUids)
            ->where('createtime < ?', $createTime)
            ->where('tousers', '1')
            ->limit(($page - 1) * $perpage, $perpage)
            ->order('createtime DESC')
            ->fetchAll();
    }
```
最终获取日历事件列表的底层实现代码如上所示，并在调试模式下，可以看到对应的SQL语句为：  
```sql
SELECT id, uid, title, content, createtime FROM wt_event WHERE (uid IN (2, 1)) AND (createtime < ?) AND (tousers = '1') ORDER BY createtime DESC LIMIT 0,5; -- '2017-05-29 59:59:59'
```
如果你觉得这时已经大功告成，那就错了，因为严谨的单元测试会告诉你，还缺少了用户的相关信息，例如用户头像。  

那么，对于用户的相关信息，应该在哪里实现呢？又应该在哪里调用呢？实现的位置不容置疑，应该是在用户领域业务为Domain_User中，但调用的时机呢？很明显，这属于在在日历事件列表中聚合用户信息，这是属于根据不同的业务场景而组合的数据，因此应该在日历事件领域业务类中完成这一组装的过程。既然这样，需要在已经获得的日历事件列表中继续追加用户信息，添加新的代码后，代码看起来像是这样。  
```php
// WeTime$ vim ./Fun/Domain/Event.php
    public function getSpaceList($userId, $createTime, $perpage = 20, $page = 1) {
        ... ...
        $model = new Model_Event();
        $list = $model->getSpaceList($allUids, $createTime, $perpage, $page);

        $domainUser = new Domain_User();
        $userList = $domainUser->getUserList($allUids);
        foreach ($list as &$eventRef) {
            $eventRef['user'] = array(
                'avatar' => $userList[$eventRef['uid']]['avatar'],
            );
        }
     
        return $list;
    }
```
细心品读这段代码，可以发现一些有趣的事情。首先，这里先是批量获取了全部的用户信息，再追加相应的用户数据。这种做法既不是每次重复查询数据库获取用户信息的粗暴方式，也不是在Model层进行关联查询却产生过度数据耦合及技术实现耦合的方式。其次，在添加用户信息时，采用的是对每个事件条目按需追加，如这时只追加了头像这一信息。这样是考虑到可共用的用户列表信息，很有可能会有后期添加一些扩展字段，但这些字段不一定是客户端所需要的。所以，通过先编写调用代码，可以迫使我们开发人员优先考虑需要什么，再去实现。  

这里又是一条开发支线，但这条支线已经是“最后一公里”了。完成这条支线的功能开发，如无意外，我们就能交付查看日历事件列表这一接口服务了！    

先来实现领域业务层获取用户列表的方法：  
```php
// WeTime$ vim ./Fun/Domain/User.php
<?php
/**
 * 用户领域业务类
 */

class Domain_User {
 
    public function getUserList($allUids) {
        $model = new Model_User();
        return $model->getUserList($allUids);
    }
}
```
再来实现对应的数据模型层的方法：  
```php
// WeTime$ vim ./Fun/Model/User.php
<?php
/**
 * 用户数据模型类
 */
class Model_User extends PhalApi_Model_NotORM {
 
    public function getUserList($allUids) {
        $rows = $this->getORM()
            ->select('id, avatar')
            ->where('id', $allUids)
            ->fetchAll();

        $list = array();
        foreach ($rows as $row) {
            $list[$row['id']] = $row;
        }

        return $list;
    }
}
```
值得注意的是，为了方便调用方更方便找到特定用户的信息，这些将查询到的数据库结果集转换成了以用户ID为下标的数组，再返回。  

到这一步，再次执行单元测试，可以发现终于通过了！并且，在调试模式下，可以看到整个过程中，所执行的数据库操作有：  
```bash
WeTime$ phpunit --filter testSpace ./Fun/Tests/Api/Api_Event_Test.php 
/path/to/meet/src/WeTime/Fun/Model/Follow.php:12:SELECT touid FROM wt_follow WHERE (uid = 1);

/path/to/meet/src/WeTime/Fun/Model/Event.php:13:SELECT COUNT(id) FROM wt_event WHERE (uid IN (2, 1)) AND (createtime < ?) AND (tousers = '1'); -- '2017-05-29 59:59:59'

/path/to/meet/src/WeTime/Fun/Model/Follow.php:12:SELECT touid FROM wt_follow WHERE (uid = 1);

/path/to/meet/src/WeTime/Fun/Model/Event.php:23:SELECT id, uid, title, content, createtime FROM wt_event WHERE (uid IN (2, 1)) AND (createtime < ?) AND (tousers = '1') ORDER BY createtime DESC LIMIT 0,5; -- '2017-05-29 59:59:59'

/path/to/meet/src/WeTime/Fun/Model/User.php:12:SELECT id, avatar FROM wt_user WHERE (id IN (2, 1));

... ...

OK (1 test, 55 assertions)
```
如果通过浏览器来访问此接口服务，并传递与测试用例同样的参数，可以看到类似这样的返回结果。  
```
{
    "ret": 200,
    "data": {
        "total": 7,
        "perpage": 5,
        "page": 1,
        "list": [
            {
                "id": "4",
                "uid": "1",
                "title": "测试事件",
                "content": "这是一个测试事件",
                "createtime": "2017-05-29 10:03:34",
                "user": {
                    "avatar": "/images/aevit.jpg"
                }
            },
            ... ...
            }
        ]
    },
    "msg": ""
}
```

至此，在意图导向下我们已经完成查看日历事件列表这一具体功能的开发，并让测试通过了。每一个阶段的结束，都是下一个阶段的开始。在完成具体功能后，接下来需要进行适当的重构，追求更高的代码质量。还记得前面被我们有意绕过的问题吗？就是那段在Domain_Event类重复的调用代码片段，这些重复的调用代码不仅散发着代码异味，同时也导致了重复查询数据库的问题。下面，一起来看下，如何通过重构解决这些问题。  

对于在类方法内重复的代码，可以考虑使用提取子函数的重构方式。例如，这里将获取用户关注的用户ID列表这一调用代码，提取到一个保护级别的函数成员中，并把原来的调用方式改为对此新增函数成员的调用。重构后的代码是：  
```php
// WeTime$ vim ./Fun/Domain/Event.php
    public function getSpaceTotal($userId, $createTime) {
        $allUids = $this->getAllUidsForEvent($userId);
        ... ...
    }

    public function getSpaceList($userId, $createTime, $perpage = 20, $page = 1) {
        $allUids = $this->getAllUidsForEvent($userId);
        ... ...
    }
    
    protected function getAllUidsForEvent($userId) {
        $domainFollow = new Domain_Follow();
        $followUids = $domainFollow->getFollowUids($userId);
        return array_merge($followUids, array($userId));
    }
```
别忘了在每次小步重构后，执行一下单元测试，确保原来的功能没有受到影响。虽然消除了代码上的重复，但尚未解决重复查询数据库的问题。但这个问题也很好解决，只需添加一层程序级别的缓存即可，以便在同一次请求中可以重用已经获取的数据。这时关注点主要集中在```Domain_Event::getAllUidsForEvent($userId)```类方法内部，为其返回的结果添加程序级缓存后的代码片段是：  
```php
// WeTime$ vim ./Fun/Domain/Event.php
class Domain_Event {
    protected static $uidsCache = array();
    
    protected function getAllUidsForEvent($userId) {
        if (!isset(self::$uidsCache[$userId])) {
            $domainFollow = new Domain_Follow();
            $followUids = $domainFollow->getFollowUids($userId);
            self::$uidsCache[$userId] = array_merge($followUids, array($userId));
        }

        return self::$uidsCache[$userId];
    }    
```
再次执行单元测试，确保一切安好。重构后，可以发现原来需要查询5次数据库，这时只需要查询4次数据库，减少了一次没必要的数据库查询，优化了接口服务的响应性能。细心的读者可能已经察觉到，在Model_Event类中也存在部分重复代码，虽然不明显但却客观存在，即重复的where条件。此部分的代码重构留给感兴趣的读者作为练习。    

接下来，需要为新增的领域业务类和数据模型类补充对应的测试代码。这里不再展开，留给读者亲自进行操作实践。  

补充好这些测试用例后，最后执行单元测试套件，确保全部测试通过，没有引入新的问题。如果全部测试通过，恭喜你！可以提取下班了。

### 5.3.5 操作日历事件接口服务的开发

在前面，在遵循测试驱动开发和按照一般开发步骤，我们详细讲解了发布事件与获取事件列表这两个接口服务的开发过程。相信大家对具体接口服务的开发流程有了一定的理解。现在剩下日历事件模块最后一个接口服务——操作日历事件接口服务。下面，让我们快马加鞭，完成这个接口服务的开发。  

鉴于这是第三个接口服务的开发，下面的开发流程不再对一般的开发步骤作过多的说明，而着重说明如何实现业务功能和一些需要注意的事项。  

操作日历事件接口服务，主要作用是把某个日历事件的状态改为已删除、未完成或已完成。日历事件在发布时，初始状态是未完成，即激活状态。在开发具体的业务功能时需要注意的一点是，通常在进行删除操作时，不会进行物理删除，而是进行逻辑删除。也就是说，不会直接在数据库中把数据纪录删除，而是把某个数据库表字段的值设置为删除状态，如这里的state为0时，表示事件已删除。  

既然日历事件有三种状态，那么在测试这三种状态的更新时，可以使用phpunit中一个有趣的注解，即```@dataProvider```注解。一如既往，先准备测试用例。假设已存在一条ID为5的日历事件，由Aevit发布，下面将模拟Aevit对这条事件进行不同的状态操作。  

```php
// WeTime$ vim ./Fun/Tests/Api/Api_Event_Test.php
    /**
     * @dataProvider allEventState
     */
    public function testOperate($state, $sign)
    {
        // Step 1. 构建
        $url = 'service=Event.Operate&client=ios&version=1.0.1&user_id=1&sign=' . $sign;
        $params = array(
            'event_id' => 5,
            'state' => $state,
        );

        // Step 2. 执行
        $rs = PhalApi_Helper_TestRunner::go($url, $params);

        // Step 3. 验证
        $this->assertEquals(1, $rs['code']);
    }

    public function allEventState()
    {
        return array(
            array('0', '1ee57808737cfe96c324a252046d63d1'),
            array('1', 'ae13b4d11cdcf70954a81765d2b00a2f'),
            array('2', '8d5ec54e845337eda957f2c97dab5197'),
        );
    }
```
通过```@dataProvider```注解，为测试用例准备了三组测试数据，为此，当执行此单元测试时，会分别使用这三组数据进行三次测试，分别把ID为5的日历事件状态依次更新为已删除、未完成、已完成。最后，断言操作的结果为成功，用1表示。日历事件不存在时返回0，而事件状态未发生改变时返回布尔值FALSE。这与更新数据库纪录返回的结果是一致的。又由于准备的三组测试数据是依次循环的三种不同状态，所以可以保证正常情况下通过操作日历事件接口服务更新后，返回的结果应该都是成功的。下面是执行本次单元测试的命令，并可以看到对应失败的3个测试用例和断言次数。    
```bash
WeTime$ phpunit --filter testOperate ./Fun/Tests/Api/Api_Event_Test.php 
... ...
FAILURES!
Tests: 3, Assertions: 3, Failures: 3.
```

虽然测试用例有点复杂，但内部的实现相对而言，非常简单。简单到可以一气呵成，完成全部的开发。以下是Api层的实现代码。  
```php
// WeTime$ vim ./Fun/Api/Event.php
class Api_Event extends PhalApi_Api {

    public function getRules() {
        return array(
            ... ...
            'operate' => array(
                'id' => array('name' => 'event_id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '事件ID'),
                'state' => array('name' => 'state', 'type' => 'enum', 'require' => true, 'range' => array('0', '1', '2'), 'desc' => '状态（0：已删除；1：未完成；2：已完成）'),
            ),
        );
    }

    public function operate() {
        if ($this->userId <= 0) {
            throw new Phalapi_Exception_InternalServerError('用户未登录');
        }

        $domain = new Domain_Event();
        $code = $domain->operate($this->userId, $this->id, $this->state);
     
        return array('code' => $code);
    }
```
> 温馨提示：接口参数state配置为枚举类型，选项中range的枚举值应该是字符串类型，而非整型。一来避免PhalApi产生误判，二来与数据库表结构的枚举值完全匹配。  

下面是Domain层的实现代码。目前看来，这只是简单地透传数据，但在后期可以迭代添加更多细化的业务规则。例如，对于已删除的事件，不能再恢复为未完成或已完成。即事件状态的设置有一定的顺序，如只能按“未完成-已完成-删除”这样的顺序设置。
```php
// WeTime$ vim ./Fun/Domain/Event.php
class Domain_Event {
    public function operate($userId, $id, $state) {
        $model = new Model_Event();
        return $model->operate($userId, $id, $state);
    }
}
```

最后，是Model层的实例，主要是数据库的更新操作。  
```php
// WeTime$ vim ./Fun/Model/Event.php
class Model_Event extends PhalApi_Model_NotORM {
    public function operate($userId, $id, $state) {
        return $this->getORM()
            ->where('uid', $userId)
            ->where('id', $id)
            ->update(array('state' => $state));
    }
}
```

至此，操作日历事件的接口服务就开发完成了。运行一下单元测试，可以看到失败的3个测试用例已经全部通过测试。除此之外，在调试模式下，还可以看到所执行的SQL语句。类似下面这样。  
```bash
WeTime$ phpunit --filter testOperate ./Fun/Tests/Api/Api_Event_Test.php 

/path/to/meet/src/WeTime/Fun/Model/Event.php:32:UPDATE wt_event SET state = '0' WHERE (uid = 1) AND (id = 5);

/path/to/meet/src/WeTime/Fun/Model/Event.php:32:UPDATE wt_event SET state = '1' WHERE (uid = 1) AND (id = 5);

/path/to/meet/src/WeTime/Fun/Model/Event.php:32:UPDATE wt_event SET state = '2' WHERE (uid = 1) AND (id = 5);
... ...
OK (3 tests, 3 assertions)
```

到这里，日历事件模块的接口服务就可以暂告一段落了。休息一下，我们将会开发一个经常在项目中会用到的接口服务。那就是——

### 5.3.6 图片上传接口服务的开发

在项目中经常会用到的一个基础性接口服务是图片上传服务。例如WeTime项目中，需要上传用户头像、上传事件的图片素材、设置订阅分组的封面图片等。因为这里将要实现的图片上传功能，是客户端通过表单方式进行上传的，为了更接近真实的操作效果，我们在这个特殊的场景中暂不使用单元测试来模拟文件上传，而通过一个脚手架来进行手动测试。这个脚本架就是一个可用于上传图片的简单表单，并可对外访问。对应的HTML代码和文件是：    
```html
// $ vim ./Public/fun/test_upload_img.html
<html>
    <form method="POST" action="/fun/?service=Resource.uploadImg&sign=15f016ddd7ffaed566f8c215cf8de2ef" enctype="multipart/form-data">
        <input type="file" name="img">
        <input type="submit">
    </form>
</html>
```
这时选择图片，点击提交会提示接口服务Resource.uploadImg不存在。下面让我们来添加它，然后实现它。图片上传接口服务需要一个必须参数，那就是待上传的图片文件。这里可以使用PhalApi的文件类型file，并且设置最大允许文件大小、文件格式和扩展名等。下面是设置了最大允许文件大小为2M，而且只允许上传jpeg和png图片格式的参数配置。   
```php
// WeTime$ vim ./Fun/Api/Resource.php
<?php
/**
 * 资源接口类
 */
class Api_Resource extends PhalApi_Api {
 
    public function getRules() {
        return array(
            'uploadImg' => array(
                'img' => array(
                    'name' => 'img', 
                    'type' => 'file', 
                    'require' => true, 
                    'max' => 2097152, // 2M = 2 * 1024 * 1024, 
                    'range' => array('image/jpeg', 'image/png'), 
                    'ext' => 'jpeg,jpg,png', 
                    'desc' => '待上传的图片文件',
                ),
            ),
        );
    }   

    public function uploadImg() {
    }
}
```
在学习摄影如何构图的过程中，很多人都会跟初学者说，先学会基本的构图方式，再去打破它。学习编程也是，我们需要先掌握基本的ADM模式，再去打破它。一如这里的图片上传接口服务，由于它主要功能是简单地把表单上传的图片文件保存到本地服务器，然后返回可访问的相对路径。可以看到，这一过程，更多是技术方面的处理，没有过多的业务规则，也不需要涉及到数据库这些数据。因此，可以考虑在Api层完成这一接口服务的全部功能开发。在Api层，具体过程是接收PhalApi处理好的文件数据，然后保存上传的图片文件在可访问的./Public/upload目录下，最后返回上传成功的图片路径。下面是对应的实现代码。  
```php
// WeTime$ vim ./Fun/Api/Resource.php
    public function uploadImg() {
        $rs = array('code' => 0, 'url' => '');

        $tmpName = $this->img['tmp_name'];

        $name = md5($this->img['name']);
        $ext = strrchr($this->img['name'], '.');
        $imgPath = sprintf('%s/Public/upload/%s%s', API_ROOT, $name, $ext);

        if (move_uploaded_file($tmpName, $imgPath)) {
            $rs['code'] = 1;
            $rs['url'] = sprintf('//%s/upload/%s%s', $_SERVER['SERVER_NAME'], $name, $ext);
        }

        return $rs;
    }
```
在上面实现的代码中，有两个细节。一个是对上传的文件名进行了简单的MD5转换，确保新的图片文件名符合操作系统的命名规则。另一个是返回的图片访问链接使用是的双斜线开头，便于同时兼容HTTP和HTTPS这两种访问协议。  

功能实现好后，重新尝试上传图片，如果提示没有./Public/upload这个目录，则需要手动添加，并赋以相应的读写权限。如：  
```bash
WeTime$ mkdir ./Public/upload
WeTime$ chmod 755 ./Public/upload
```
再次尝试上传图片，成功的情况下可以看到类似这样的返回：  
```
{
    "ret": 200,
    "data": {
        "code": 1,
        "url": "//api.wetime.com/upload/4bb2ec08c96a5323771b0fa8206a8114.jpg"
    },
    "msg": ""
}
```
其中code为上传的状态码，1为成功，0为失败。url为成功上传后的图片路径。通过这个接口服务，我们就可以在客户端完成用户头像等图片的上传操作了。  

### 5.3.7 在完成的基础上追求完美

在编写第一个接口服务的代码时，要特别注意。因为编写的不仅仅是第一个可用的业务功能模块，更是在奠定了当前系统项目在相当长一段时间内的代码风格，开发方式，甚至团队文化。一个公司所发展的规模，源于它最初的愿景。为公司或者为其他组织、其他目的而编写代码，创建价值，是我们作为软件开发人员的外在责任，也是我们最大的骄傲。那软件开发人中内在的责任是什么呢？那就是提高软件内部的质量，在完成的基础上追求完美。  

虽然Facebook推崇“完成胜于完美”这一原则，但是在中国，至少截止目前为止，在我所遇到过的很多软件项目都只是停留在了功能完成这一基本的要求线上。满足业务功能的代码，只能算得上是合格的代码。因为除此之外，每一行代码都应该是深思熟虑的，有着各种难得的品质。它应具备安全的属性，能抵制外界恶意的输入和非法的攻击；它应该在性能方面是最优的，不会做过多不必要重复的操作；它应该有着一致的编程风格，易于理解，可读性强。更为复杂的是，它除了能顺畅与各个类共同协作完成当前任何之外，它还需要能很好地支撑未来的变化。可以说，代码最终的样子，不仅取决于开发人员对过去需求的理解，还取决于现在所遇到的问题，和对未来的思考。我想，对于任何一位有追求的专业软件开发工程师，他所编写的任何一行代码，都不是随意而为的，而是精心雕琢，深入思考而来的。如果用一个词来概括，那就是：慎终追远。 

回过头来看一下，我们目前已经在WeTime项目中编写的代码有哪些，检验一下我们是否产出了合格之上的代码。按ADM分层模式，主要有以下代码文件：  
```bash
WeTime$ tree ./Fun/
./Fun/
├── Api
│   ├── Event.php
│   └── Resource.php
├── Domain
│   ├── Event.php
│   ├── Follow.php
│   └── User.php
├── Model
│   ├── Event.php
│   ├── Follow.php
│   └── User.php
```
其中共有4个接口服务，如果对这些隐藏在代码中的接口服务不好统计，可以通过在线接口列表文档来可视化查看。如图5-4，在日历事件接口类中有3个接口服务，而在资源接口类中有1个接口服务，加起来来共4个接口服务。

![](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-list-apis-fun.jpg)

图5-4 WeTime项目当前的在线接口列表文档

如所你所看到的，这是一个好的开始，但也是一个基本的开始。因为还有很多细节需要完善，一如与在线文档对应的代码注释。关于这些待完善的问题，在下一章的项目开发过程中，会进一步完善。但对于初级开发工程师，让我们暂时先达到完成的标准，然后在这基础上再追求完美。 

### 5.3.8 再谈单元测试驱动开发

在前面，与其说是如何开发接口服务的详细过程，还不如说是如何进行单元测试驱动开发的完全过程。从一开始，通过测试用例确定最终需要实现的功能和效果，能帮助开发人员始终保持正确的关注点，就像在茫茫大海航行过程中始终不会偏离航道。这是一个可确定，可预测，自顶而下的开发过程。既然有章可循，那么不管是初级开发工程师，笔者，还是高级开发工程师，按照这样的开发方式，都能得到一个可确定的开发过程和一个可预测的开发结果。有序，则意味着在管理软件复杂度上，我们找到了一种行之有效的应对方案。另一方面，如果采用的是不确定，甚至是杂乱无章的开发过程，那么最终产出和交付的项目代码，也会因此充满不确定性，未知因素和不可控的环节，以及意料之外的缺陷。  

回顾一下，我们为日历事件模块编写的单元测试，主要集中在./WeTime/Fun/Tests/Api/Api_Event_Test.php文件。通过phpunit的```--coverage-html <dir>```生成的代码测试覆盖率报告，类似图5-5，可以看到当前开发阶段的代码测试覆盖情况。 

![图5-5 测试覆盖率报告 ](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-coverage-event.jpg)  

图5-5 WeTime项目整体测试覆盖率报告  

在测试覆盖率报告上，点击进去，可以深入了解每个目录，每个类，每个方法，每行代码的测试覆盖情况。如图5-6，对于```Api_Event::operate()```类方法，第71行代码为红色，表示为未执行，即意味着尚未被单元测试覆盖到。

![图5-6 测试覆盖率报告 ](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-coverage-line.jpg)  

图5-6 Api_Event接口类的测试覆盖率报告

这是因为在前面，我们都没有检测过用户未登录下的操作场景。而正是这些未经测试执行的代码，往往更容易产生BUG。曾经有一次，有位开发很坚信他所写的代码没有任何问题，而且他所编写的代码也经过了严格的单元测试，只是还有一行代码尚未覆盖到。那也是一行抛出异常的代码，但当我为那一行代码而完善单元测试后，却发现那行代码抛出的异常类的名称拼写错了。拼写错误是开发人员经常会不小心犯的错，但如果单纯依赖代码走查，受心理作用和视觉疲劳影响，发现错误拼写的概率较低。这里，再一次科学客观地表明，100%的代码测试覆盖率，更能保证代码的质量。
  

接口服务，应该为它的客户端的业务而服务。有怎样的业务场景，就应该提供怎样的接口服务。而不是反过来，不应该是已有的接口服务决定业务场景。通俗地表达就是，技术应该支撑业务，而不应限制业务。从宏观的角度上看，在单元测试驱动下所开发的接口服务正是与“技术支撑业务”这一理念是吻合的。因为它从一开始就会考虑客户端的业务场景，需要哪些返回字段，需要进行怎样的数据交互，需要提供哪些服务功能。从微观的角度上看，从Api接口层到Domain层，再到Model层，都是先确定当前上下文场景的客户端需要什么（即确定调用过程），再深入到具体如何实现（即实现内部技术细节），这一过程也是体现了“底层支撑高层”这一理念。这样有什么好处呢？比较明显的一点好处是，开发工程师的效率会变得更高，因为他们不会做过多的无用功，所做的事都是以最小的代码完成当前的功能需求。在这种情况下产生的代码会更符合KISS原则，自然编写的代码就会更优雅。能提高开发效率的另一个原因是因为单元测试会一步步指导开发人员接下来需要做什么，就像寻宝时的宝藏图，从而减少中间冥想的时间。  

再来看下单元测试驱动开发是如何降低软件开发复杂度的。让我们来回顾一下查看日历事件列表这接口服务的非正式的协作泳道图，如图5-7所示。其中，虚线部分为重复的节点。可以看出，查看日历事件列表这一接口服务只是使用数据库实现了基本功能，还没算上使用高效缓存和其他更复杂的业务规则，就已经涉及了1个Api接口类方法，4个Domain领域类方法，和4个Model类方法，累计共9个类方法，对于正常的开发人员来说，是有一定的复杂度的。而且这9个类方法要以指定的顺序依次执行，并且要保证每个类方法都能正常地工作，就难上加难了，更别提还要要求产出的代码是灵活、优雅、容易维护的。  

![](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-adm-space.jpg)

图5-7 查看日历事件列表接口服务的协作泳道图  

通过单元测试驱动开发，通过失败的测试用例，则可以让开发人员在同一时间只关注一件事件，只做一件事情，而不用一下子同时考虑9个类方法，同时做9件事件。相比于传统的“开发-调试”方式，“红-绿-重构”方式的开发思路更清晰，所要同时面对的软件复杂度更低，从而产出的代码质量更高。

但单元测试驱动开发也不是银弹，而且也有其要求。想要娴熟应用它并从中获益，不仅要求开发人员熟练对单元测试的基本使用，还要求开发人员对业务需求有清晰的理解，能一开始在概念层确实具体需要实现的功能。此外，在上面所提及的都是Happy Path的测试路径，更完善的测试用例应该参考三角验证进行编写，即需要考虑更多异常场景下的应对。这里不再展开。


## 5.4 与客户端的联调

在完成对接口服务的开发后，便可把对应的接口服务提供给客户端使用，并通过说明文档告知客户端如何调用。结合客户端的开发，便可看到最终展示在界面上的数据，而非再是单调的JSON格式数据。 

### 5.4.1 与移动App的联调

在WeTime项目中，移动App主要包括有iOS客户端和Android客户端。根据项目的情况，需要支撑的客户端也不尽相同，有的可能还需要支撑H5混合页面，支撑Windows Phone。尽量与客户端进行联调有很多好处。首先，可以有效保证项目进度，有客户端需要时甚至在客户端开始开发前就提供所需的接口服务，有助于保障项目不阻塞，不延期。其次，通过客户端在真实业务场景中使用接口服务，更有利于提前发现问题以及不合理的设计，从而在前期就进行调整，避免到了后期大量的返工。最后，在完成开发接口服务后，在后端开发人员记忆犹新时尽快和客户端进行联调，也有助于快速定位解决问题。  

如iOS客户端的个人页面，展示效果如下：  

![图5-8 iOS客户端的个人页面](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-ios-user-center.jpg)

图5-8 iOS客户端的个人页面

又如Android客户端的个人页面，展示效果如下：  

![图5-9 Android客户端的个人页面](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-android-user-center.png)

图5-9 Android客户端的个人页面

当客户端需要调用接口服务时，可以将PhalApi封装的SDK提供给客户端开发工程师。目前客户端SDK包已支持的开发语言有：  

 + JAVA
 + Objective-C
 + PHP
 + C#
 + Javascript
 + Go
 + Ruby
 + Python

客户端也可以根据自己的实际情况，封装专属的接口服务请求类。  

在与客户端联调的过程中，往往会发现一些有趣的问题。例如字符编码问题，返回字段的类型问题，部分接口服务的签名验证等。简而言之，实践出真知。

### 5.4.2 与管理后台的联调

WeTime项目的一个特色是它的管理后台的全部数据，也是通过接口服务来获取或操作的。这与以往的管理后台的实现方式不一样，传统方式是管理后台直接操作数据库，但对于存在多个客户端的架构中，直接操作数据库不能保证业务规则的一致性。因此，尽早考虑打造项目的接口中间层，统一业务规则，业务项目可以从中获益。  

提供给管理后台的接口服务，与提供给移动App的接口服务没有明显的差异，但存在微妙的区别。首先，是访问接口服务模块的划分。因为在管理后台往往需要一些更高级别、能操纵核心业务数据的功能，因此把管理后台的接口服务与移动App业务的接口服务划分在不同的模块，有助于保护接口服务的受限访问。另外，管理后台通常是提供给内部人员使用的，是针对内部的客户端，而移动App是提供给最终用户使用的，即针对市场的客户端。对于这些内部的操作流程，很多时候都需要对每次操作进行详细的纪录，以便进行业务上的复核与审计。即相比于提供给最终用户的接口服务，提供给内部人员的接口服务，在实现时更需要加强权限管理，操作纪录。  

WeTime的管理后台，是基于Yii框架开发的，前端则使用了Bootsrap和jQuery进行页面布局和交互。下面是在管理后台最终查看订阅号的运行效果局部截图。  

![图5-10 管理后台查看订阅号页面](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-manager-user.jpg)

图5-10 管理后台查看订阅号页面

对于此管理后台，可以使用PhalApi提供的PHP版SDK包，也可以自行封装对接口服务的请求。假设已封装在PhalApiClient类，在实现类似图5-10的展示类功能时，与以往实现方式不同的是，现在需要通过PhalApiClient类从远程的接口服务获取用户的信息，而不再是直接数据库中获取。其实现的代码片段是：  
```php
    public function actionUserManagerShow()
    {
        $this->pageCurPosition['订阅号管理'] = '?r=DailyOperations/userManager';
        $this->pageCurPosition[] = '订阅号查看';
        // 更多页面设置 ... ...

        $userId = isset($_REQUEST['userId']) ? intval($_REQUEST['userId']) : 0;
        if ($userId <= 0) {
            $this->redirect('?r=DailyOperations/userManager');
            return;
        }

        $tip = '<br/>';
        $status = null;
        $dataProvider = null;
        $totalUserCount = 0;
        $userInfo = array();

        $model = new UserForm();
        $model->userId = $userId;

        $apiClient = new PhalApiClient();
        $apiClient->request('User.Info', array('otherUserId' => $userId));		

        $tip .= $apiClient->getMsg();
        $status = $apiClient->getRet();

        if ($apiClient->getRet() == PhalApiClient::STATUS_OK) {
            $data = $apiClient->getData();
            $userInfo = $data['user'];

            $model->attributes = $userInfo;
            $model->userId = $userId;
            // 更多对返回字段的处理 ... ...
        }

        // 页面渲染
        if (empty($userInfo)) {
            $this->render('confirm', 
                array(
                    'title' => '用户不存在或者已被删除', 
                    'msg' => $apiClient->getMsg(), 
                    'jumpUrl' => '?r=DailyOperations/userManager', 
                    'backUrl' => '?r=DailyOperations/userManager')
                );
            return;
        }

        $this->render('userManagerShow', array('tip' => $tip, 'status' => $status, 'model' => $model, 'userInfo' => $userInfo));
    }
```
上面这段代码有点长，但不难理解。先是对页面左侧菜单、页面标题的页面设置，接着是准备和初始化页面数据，以便请求远程接口服务```User.Info```获取指定用户的信息。随后是对接口返回的结果进行处理，以及对页面的渲染处理。  

对应的模板页面的代码为：  
```php
<h3>基本信息</h3>

<table class="table table-hover">
      <thead>
        <tr>
          <th width="140px">用户ID</th>
          <th><?php echo $model->userId; ?></th>
        </tr>
      </thead>

      <tbody>
        <tr width="140px">
            <td>头像</td>
            <td><img id="show_UserForm_file" class="img-thumbnail" src="<?php echo !empty($model->avatar) ? $model->avatar : '/images/WeTim_Logo_128.png'; ?>" style="width:100px;"></td>
        </tr>
        <tr>
          <td>用户名/登录账号</td>
          <td><?php echo $model->username; ?></td>
        </tr>
        <tr>
          <td>昵称</td>
          <td><?php echo $model->nickname; ?></td>
        </tr>
        <tr>
          <td>用户类型</td>
          <td><?php echo $model->userType == 1 ? '<font color="red">自媒体</font>' : '<font color="blue">普通用户</font>'; ?></td>
        </tr>
        <tr>
          <td>来源的头像Url</td>
          <td><?php echo $model->comePic; ?></td>
        </tr>
        <tr>
          <td>来源的Url</td>
          <td><a href="<?php echo $model->comeUrl; ?>" target="_blank"><?php echo $model->comeUrl; ?></a></td>
        </tr>
        <tr>
          <td>来源的名称</td>
          <td><?php echo $model->comeName; ?></td>
        </tr>
        <tr>
          <td>粉丝数量</td>
          <td><?php echo $model->fans; ?></td>
        </tr>
      </tbody>
</table>
```

又如我们熟悉的日历事件发布，在管理后台最终发布日历事件的运行效果局部截图，请见5-7。  

![图5-11 管理后台发布日历事件页面](http://7xiz2f.com1.z0.glb.clouddn.com/ch-5-manager-event-post.jpg)

图5-11 管理后台发布日历事件页面

可以看到，这里也同样有图片上传功能，也需要进行事件发布，因此可以很好地重用已有的接口服务，而不再需要在管理后台系统重复实现。对于此日历事件发布，在管理后台对应的实现代码片段是：  
```php
	public function actionEventAdd()
	{
		$this->pageCurPosition[] = '事件添加';
		$this->setPageTitle($this->headerTitle . ' - ' . Yii::app()->name);
		$this->pageLeftBarPos = 12;
		
		$tip = '';
		$status = null;
		
		$model = new EventForm();
		$model->time = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
		
		if (isset($_POST['EventForm'])) {
			$model->attributes = $_POST['EventForm'];
			if($model->validate()){
				$tip .= '<br/>';
				
				$model->pics = isset($_POST['pics']) && is_array($_POST['pics']) ? $_POST['pics'] : array();
			
				// 调用接口服务发布日历事件
				$apiClient = PhalApiClient::getInstance();
				$apiClient->request('Event.Post', $model->toArray());
				
				if ($apiClient->getRet() == PhalApiClient::STATUS_OK) {
					$apiData = $apiClient->getData();
					if ($apiData['code'] == 0) {
						$tip .= sprintf('已经为用户：<strong>%s</strong>，成功添加事件：<strong>%s</strong>', $model->userId, $model->title);
						$status = PhalApiClient::STATUS_OK;
					} else {
						$tip .= sprintf('<font color="red">添加失败，code = %s </font>', $apiData['code']);
					$status = PhalApiClient::STATUS_WRONG;
					}
				} else {
					$tip .= sprintf('<font color="red">添加失败，错误信息：%s </font>', $apiClient->getMsg());
					$status = PhalApiClient::STATUS_ERROR;
                }
			}
		}
		
		$this->render('eventAdd', array('tip' => $tip, 'status' => $status, 'model' => $model));
	}
```
上面的代码，实现的功能主要是，对页面进行设置，收集表单数据并调用远程的日历事件发布```Event.Post```接口服务，最后处理返回的结果并渲染页面。  

管理后台的查看订阅号页面调用的是查询类接口服务，而发布日历事件则调用了命令类的接口服务。虽然通过接口服务可以更有效地维护业务规则的一致性，但对于需要分页的情况，则没有直接操作数据库那样方便。但也正如人们常说编程是一门艺术，也是一项需要综合权衡的工程。任何一种架构设计，都会有其优点和缺点。在实际项目开发过程中，需要作为开发人员的我们仔细评估，采用最适合当前项目的解决方案。例如在这里，相比于维护业务规则的一致性，损失了直接操作数据库的便利性，我觉得，从长远的角度上考虑，是值得的。

## 5.5 上线发布

在开发完成接口服务，也完成与客户端的联调后，下一步则可以发布上线了。在正式开发流程中，应该通过严格的测试后，通过QA部门的验收后，方可发布上线。但在这里，假设的是非正式开发流程，因此没有专门的测试人员，而是在开发人员自测通过后，就发布上线。

### 5.5.1 上线checklist

《清单革命》一书中，分享了如何通过清单有效地减少出错的概念。在把项目代码发布到生产环境前，准备一份上线checklist也是大有裨益的，尤其是首次上线。  

首次发布，上线checklist包括但不限于：  

 + 线上运行环境是否已部署？  
 + Nginx配置是否已就绪，系统站点是否已可访问？  
 + 数据库是否已存在，数据库表是否已创建？
 + PHP是否已安装，所需的扩展是否已安装，php-fpm是否已启动？
 + 项目目录、日志目录、上传目录等是否已分配相应的权限？


日常发布，上线checklist包括但不限于：  

 + 所需的系统环境变更是否已执行？
 + 所需的Nginx配置变更是否已执行？
 + 所需的数据库变更是否已执行？
 + 所需的PHP变更是否已执行？
 + 所依赖的第三方系统是否已上线，是否可用？
 + 所需要的计划任务是否已添加？

在WeTime项目首次发布时，主要是要部署线上环境，其过程与前面在部署开发环境类似，这里不再赘述。有一点不同的是，在线上使用的是真实的公网域名，需要进行DNS解析。

### 5.5.2 通过FTP或其他方式发布

当线上环境部署好后，最后就是要把最新版本的代码发布到生产环境。WeTime项目的接口系统api.wetime.com只部署在单台服务器上，因此暂时不需要均衡负载，并且代码包发布的方式非常简单，因为只需要更新一台服务器上的项目代码即可。这时，可选用通过FTP实现文件上传，或者使用Git在服务器上进行更新，或者在本地将项目代码打包然后使用rz命令上传到服务器再解压等其他文件上伟方式。  

在使用Git管理项目代码时，通常以dev分支作为多人协作开发的分支，当需求功能开发完成后，由开发人员将dev分支的代码合并到主干master分支，最后将master分支的代码同步到生产环境。这种分支管理方式，虽然简单，但非常适用于小团队的项目。

### 5.2.3 发布验收

成功上线后，接下来需要通知产品人员或者项目干系人进行发布验收，确保所提供的接口服务能满足预期的业务需求。对于不能满足的业务场景，需要进行相应调整；对于遗漏的场景，需要开发补全；而对于发现的缺陷，需要及时修复。只有通过了发布验收，我们对接口服务的开发才算是交付完成。这也是项目开发流程中重要的里程碑之一。

## 5.6 项目小结

在这一章，从最初收集创业项目的需求，到最后发布上线并通过验收，我们粗略地感受了一个基本项目开发的流程。虽未亲身参与其中，却也能得到了一个初步的感观认识，对于未曾有过实际项目开发经验的学生或者初级开发工程师有一定的参考作用。类似这样通过学习而获得的项目经验，有助于帮助我们在面对新项目时，化被动为主动，因为我们已经知道在何时应该做何事。   

在本章结束前，再分享一个小故事。相传在很久以前，有个不识字的地主的儿子到了该读书的年龄，他便把小孩送去书塾开始上课，主要是学习如何用汉字写数字。第一天，老先生教会了他第一个字，即数字“一”，于是在黑板上划了一横。第二天，老先生教会了他第二个字，即数字“二”，于是又在黑板上划了两横。到了第三天，老先生在黑板上划了三横……这天回家后，小孩跟他父亲说，“原来写数字这么简单的，我已经全部学会了，不用再去上课了！”地主非常高兴，不仅为儿子的聪明感到开心，更为省下了不少学费而高兴。过了不久，到了除夕，地主想让他的儿子帮忙写“万事胜意”这四个字贴在大门口。他儿子说完全没问题，于是便进了书房准备笔墨，开始书写。可地主在门外等了大半天也没见他儿子出来，于是便进书房一探究竟。他儿子一看到父亲进来，就委屈说道：“爸，第一个‘万’字太难写了，我得划一万行……”。  

通过这个小故事，意思是如果读完了这一章，千万先别着急马上去开发项目，编写代码。因为将WeTime项目的功能模块进行逐层划分后，我们有用户、关注、日历事件等模块，而在具体接口服务的开发又聚集于日历事件模块的三个接口服务的开发。日历事件的发布、查看和操作，这些接口服务的实现虽然只是涉及到数据库而已，但也涵盖了数据库的CURD这些基本的数据库操作，以及接口参数规则配置、ADM分层模式、脚本命令的使用等内容。从前面对单个类的点学习，到高级主题的线学习，再到现在项目中的面应用，相信你应该对如何使用PhalApi进行接口服务开发，有了一定的理解。  

但是需要注意的是，这里所讲述的WeTime开发过程，并非是真实的WeTime项目的开发过程，更不代表着普遍的实际项目的开发过程。它看起来简单，简单得似乎粗糙而不合理，但这些都是有意而为的，目的是让读者更关注对基础内容的应用。而对于WeTime项目中有待改进的地方，都会在下一章的项目学习过程中，得到改善和优化。因为我们下一章学习的将是一个更全面、更正式的商业项目。  