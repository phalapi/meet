# 第2章 基础入门  

__表达，从简单开始。——Robin Williams《写给大家看的设计书》__  
  
## 2.1 接口请求

PhalApi默认使用的是HTTP/HTTPS协议进行通讯，请求接口的完整URL格式则是：  
```
接口域名 + 入口路径 + ?service=Class.Action + [接口参数]
```
下面分别进行说明。  

### 2.1.1 接口服务URI

#### (1) 接口域名

通常，我们建议对于接口服务系统，应该单独部署一个接口域名，而不应与其他传统的Web应用系统或者管理后台混合在一起，以便分开维护。  
  
假设我们已经有一个站点，其域名为：```www.demo.com```，现需要为开发一套接口服务提供给移动App使用。直接在已有站点下添加一个入口以提供服务的做法是不推荐的，即不建议接口URI是：```www.demo.com/api```。推荐的做法是，单独配置部署一个新的接口域名，如：```api.demo.com```。当前，我们也可以发现很多公司都提供了这样独立的接口平台。例如：  

 + 优酷开放平台：https://openapi.youku.com
 + 微信公众号： https://api.weixin.qq.com
 + 新浪微博： https://api.weibo.com
  
如第1章中，我们创建的接口项目，其域名为：```api.phalapi.net```。  

#### (2) 入口路径  
入口路径是相对路径，不同的项目可以使用不同的入口。如框架自带的演示项目，其目录是：```./Public/demo```，对应的访问入口路径是：```api.phalapi.net/demo```；而新建的商城Shop项目的目录是：```./Public/shop```，则入口路径是：```api.phalapi.net/shop```。这个入口路径是可选的，也可以直接使用根目录。  

#### (3) 指定接口服务
在PhalApi中，我们统一约定使用```service```参数来指定所请求的接口服务。通常情况下，此参数使用GET方式传递，即使用```$_GET['service']```，其格式为：```?service=Class.Action```。其中```Class```是对应请求的接口剔除Api_前缀后的类名，```Action```则是待执行的接口类中的方法名。当未指定service参数时，默认使用```?service=Default.Index```。  
  
如请求默认的接口服务可用```?service=Default.Index```，则相应会调用```Api_Default::Index()```这一接口服务；若请求的是```?service=Welcome.Say```，则会调用```Api_Welcome::Say```这一接口服务。  
  
#### (4) 接口参数  
接口参数是可选的，根据不同的接口服务所约定的参数进行传递。可以是GET参数，POST参数，或者多媒体数据。  

下面来看一些完整的示例。  

 + 请求默认接口服务，并省略service
```
http://api.phalapi.net/shop/  
```

 + 请求默认接口服务
```
http://api.phalapi.net/shop/?service=Default.Index  
```

 + 请求默认接口服务，并带有username参数
```
http://api.phalapi.net/shop/?service=Default.Index&username=dogstar 
```

 + 请求Hello World接口服务
```
http://api.phalapi.net/shop/?service=Welcome.Say
```

至此，我们已经基本了解如何对接口服务发起请求。接下来，让我们来看下对于接口服务至关重要的要素 —— 接口参数。    
### 2.1.2 参数规则
接口参数，对于接口服务本身来说，是非常重要的。对于外部调用的客户端来说，同等重要。对于接口参数，我们希望能够既减轻后台开发对接口参数获取、判断、验证、文档编写的痛苦；又能方便客户端快速调用，明确参数的意义。由此，我们引入了**参数规则**这一概念，即：通过配置参数的规则，自动实现对参数的获取和验证，同时自动生成在线接口文档。  
  
参数规则是一组针对各个接口服务而配置的规则数组，由```PhalApi_Api::getRules()```方法返回。  

#### (1) 一个简单的示例
假设我们现在需要提供一个用户登录的接口，接口参数有用户名和密码，那么新增的接口类和规则如下：  
```
// $ vim ./Shop/Api/User.php
<?php

class Api_User extends PhalApi_Api {

    public function getRules() {
        return array(
            'login' => array(
                'username' => array('name' => 'username'),
                'password' => array('name' => 'password'),
            ),
        );
    }

    public function login() {
        return array('username' => $this->username, 'password' => $this->password);
    }                               
}
```

当我们请求此接口服务，并类似这样带上username和password参数时：  
```
http://api.phalapi.net/shop/?service=User.Login&username=dogstar&password=123456
```
就可以得到这样的返回结果。  
```
{"ret":0,"data":{"username":"dogstar","password":"123456"},"msg":""}
```
这是因为，在接口实现类里面```getRules()```成员方法配置参数规则后，便可以通过类属性的方式，根据配置指定的名称获取对应的接口参数，如这里的：```$this->username```和```$this->password```。  

可以看到，参数规则数组的一维下标是接口类的方法名。二维下标对应的是类属性名称，三维数组中的```name```对应的是外部客户端请求的参数名称。即：  
```
    public function getRules() {
        return array(
            '方法名' => array(
                '类属性' => array('name' => '接口参数名称'),
            ),
        );
    }
```

#### (2) 更完善的示例
在实际项目开发中，我们需要对接口参数有更细致的规定，如是否必须、长度范围、最值和默认值等。 

继续上面的业务场景，用户登录接口服务的用户名参数和密码参数皆为必须，且密码长度至少为6个字符，则可以参数规则调整为：  
```
'login' => array(
   'username' => array('name' => 'username', 'require' => true),
   'password' => array('name' => 'password', 'require' => true, 'min' => 6),
),
```
配置好后，如果不带任何参数再次请求```?service=User.Login```，就会被视为非法请求，并得到这样的错误提示：  
```
{
    "ret": 400,
    "data": [],
    "msg": "非法请求：缺少必要参数username"
}
```
如果传递的密码长度不对，也会得到一个错误的返回。  

#### (3) 三级参数规则配置
参数规则主要有三种，分别是：系统参数、应用参数、接口参数。  

系统参数是指被框架保留使用的参数。目前已被PhalApi占用的系统参数只有一个，即：service参数。类型为字符串，格式为：Class.Action，首字母不区分大小写，建议统一以大写开头。  

以下是一些示例： 
```
// 推荐写法
?service=User.Login

// 正确写法（仅开头小写）
?service=user.login
// 正确写法（方法名全部小写）
?service=user.getbaseinfo

// 错误写法（缺少方法名）
?service=User
// 错误写法（缺少点号分割）
/?service=UserLogin
// 错误写法（默认只支持点号分割，其他格式须扩展实现）
?service=User|GetBaseInfo
```

> 温馨提示：service参数中的类名只能开头小写，否则会导致linux系统下类文件加载失败。 

应用参数是指在一个接口系统中，全部项目的全部接口都需要的参数，或者通用的参数。假如我们的商城接口系统中全部的接口服务都需要必须的签名sign参数，以及非必须的版本号，则可以在```./Config/app.php```中的```apiCommonRules```进行应用参数规则的配置：  
```
//$vim ./Config/app.php
<?php
return array(
    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        //签名
        'sign' => array(
            'name' => 'sign', 'require' => true,
        ),
        //客户端App版本号，如：1.0.1
        'version' => array(
            'name' => 'version', 'default' => '', 
        ),
    ),

    ... ...
```
其配置格式和前面所说的接口参数规则配置一样，都是一个规则数组。  

接口参数是指各个具体的接口服务所需要的参数，为特定的接口服务所持有，独立配置。并且进一步在内部又细分为两种：  

 + 通用接口参数规则：使用```*```作为下标，对当前接口类全部的方法有效。  
 + 指定接口参数规则：使用方法名作为下标，只对接口类的特定某个方法有效。  


例如为了加强安全性，需要为全部的用户接口服务都加上长度为4位的验证码参数：  
```
    public function getRules() {
        return array(
            '*' => array(
                'code' => array('name' => 'code', 'require' => true, 'min' => 4, 'max' => 4),
            ),
            'login' => array(
                'username' => array('name' => 'username', 'require' => true),
                'password' => array('name' => 'password', 'require' => true, 'min' => 6),
            ),
        );
    }
```
现在，当再次请求用户登录接口，除了要提供用户名和密码外，我们还要提供验证码code参数。并且，对于Api_User类的其他方法也一样。  

#### (4) 多个参数规则时的优先级
当同一个参数规则分别在应用参数、通用接口参数及指定接口参数出现时，后面的规则会覆盖前面的，即具体化的规则会替换通用的规则，以保证接口参数满足特定场合的定制要求。  

简而言之，多个参数规则的优先级从高到下，分别是（正如你想到的那样）： 
  
 + 1、指定接口参数规则
 + 2、通用接口参数规则
 + 3、应用参数规则
 + 4、系统级参数（通常忽略，当前只有service）

#### (5) 参数规则配置


### 2.2.3 过滤器与签名验证
### 2.2.4 扩展你的项目

#### (1) 如何定制接口服务的传递方式？
虽然我们约定统一使用```?service=Class.Action```的格式来传递接口服务名称，但如果项目有需要，也可以采用其他方式来传递。例如使用斜杠而非点号进行分割：```?service=Class/Action```，再进一步，使用r参数，即最终接口服务的参数格式为：```?r=Class/Action```。  

如果需要采用其他传递接口服务名称的方式，则可以重载```PhalApi_Request::getService()```方法。以下是针对改用斜杠分割、并换用r参数名字的实现示例：  
```
// $ vim ./Shop/Common/Request/Ch1.php
<?php

class Common_Request_Ch1 extends PhalApi_Request {

    public function getService() {
        // 优先返回自定义格式的接口服务名称
        $servcie = $this->get('r');
        if (!empty($servcie)) {
            return str_replace('/', '.', $servcie);
        }

        return parent::getService();
    }
}
```

实现好自定义的请求类后，需要在项目的入口文件进行注册。  
```
// $ vim ./Public/shop/index.php
DI()->request = new Common_Request_Ch1();
```

这样，除了原来的请求方式，还可以这样请求接口服务。  


原来的方式|现在的方式
---|---
?service=Default.Index|?r=Default/Index   
?service=Welcome.Say|?r=Welcome.Say   

这里有几个注意事项： 

 + 1、重载后的方法需要转换为原始的接口服务格式，即：Class.Action  
 + 2、为保持兼容性，子类需兼容父类的实现。即在取不到自定义的接口服务名称参数时，应该返回原来的接口服务。  

#### (2) 更自由的数据源
#### (3) 添加新的参数规则
#### (4) 实现项目专属的签名方案

## 2.2 响应结构与返回格式
### 响应结构
### 返回格式
### 在线调试

## 2.3 细说ADM模式
### 何为Api-Domain-Model模式？
### Api接口服务层
### Domain领域业务层
### Model数据模型层

## 2.4 配置  
前提

## 2.5 数据库操作
### 基于NotORM的操作
### CURD基本操作
### 分表策略
### 其他数据库的链接

## 2.6 缓存策略
## 2.7 日记
## 2.8 COOKIE
## 2.9 国际化