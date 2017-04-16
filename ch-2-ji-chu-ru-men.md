# 第2章 基础入门  

__表达，从简单开始。——Robin Williams《写给大家看的设计书》__  
  
## 2.1 接口请求

PhalApi默认使用的是HTTP/HTTPS协议进行通讯，请求接口的完整URL格式则是：  
```
接口域名 + 入口路径 + ?service=Class.Action + [接口参数]
```
下面分别进行说明。  

### 2.1.1 接口服务URI

#### 接口域名

通常，我们建议对于接口服务系统，应该单独部署一个接口域名，而不应与其他传统的Web应用系统或者管理后台混合在一起，以便分开维护。  
  
假设我们已经有一个站点，其域名为：```www.demo.com```，现需要为开发一套接口服务提供给移动App使用。直接在已有站点下添加一个入口以提供服务的做法是不推荐的，即不建议接口URI是：```www.demo.com/api```。推荐的做法是，单独配置部署一个新的接口域名，如：```api.demo.com```。当前，我们也可以发现很多公司都提供了这样独立的接口平台。例如：  

 + 优酷开放平台：https://openapi.youku.com
 + 微信公众号： https://api.weixin.qq.com
 + 新浪微博： https://api.weibo.com
  
如第1章中，我们创建的接口项目，其域名为：```api.phalapi.net```。  

#### 入口路径  
入口路径是相对路径，不同的项目可以使用不同的入口。如框架自带的演示项目，其目录是：```./Public/demo```，对应的访问入口路径是：```api.phalapi.net/demo```；而新建的商城Shop项目的目录是：```./Public/shop```，则入口路径是：```api.phalapi.net/shop```。这个入口路径是可选的，也可以直接使用根目录。  

#### 指定接口服务
在PhalApi中，我们统一约定使用```service```参数来指定所请求的接口服务。通常情况下，此参数使用GET方式传递，即使用```$_GET['service']```，其格式为：```?service=Class.Action```。其中```Class```是对应请求的接口剔除Api_前缀后的类名，```Action```则是待执行的接口类中的方法名。当未指定service参数时，默认使用```?service=Default.Index```。  
  
如请求默认的接口服务可用```?service=Default.Index```，则相应会调用```Api_Default::Index()```这一接口服务；若请求的是```?service=Welcome.Say```，则会调用```Api_Welcome::Say```这一接口服务。  
  
#### 接口参数  
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
http://api.phalapi.net/shop/?service=Default.Index
```

至此，我们已经基本了解如何对接口服务发起请求。  

#### 如何定制接口服务的传递方式？
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
?servcie=User.GetBaseInfo |?r=User/GetBaseInfo   

这里有几个注意事项： 

 + 1、重载后的方法需要转换为原始的接口服务格式，即：Class.Action  
 + 2、为保持兼容性，子类需兼容父类的实现。即在取不到自定义的接口服务名称参数时，应该返回原来的接口服务。  


### 参数规则
### 过滤器与签名验证
### 更自由的数据源

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