> 古云此日足可惜，吾辈更应惜秒阴。——董必武《惜时》

## 1.1 PhalApi是什么？

PhalApi，简称：π框架，是一个国内开源的PHP轻量级接口开发框架，专注于接口服务开发，支持HTTP/SOAP/RPC协议，可用于快速搭建微服务、RESTful接口或Web Services。

## 1.2 PhalApi的前世今生

### 1.2.1 前世：个人框架zenphpWS3

PhalApi最初是始于2012年个人毕业论文的项目开发需要。

由于当时课题是开发一个基于旅游轨迹的图片分享平台，需要开发一套提供给App客户端使用的后台服务接口。 然而，在实际项目开发中，发现身边很多团队在使用PHP进行接口开发时，往往是很简单，或者说是很粗爆的，如直接使用fopen\(\)函数获取远程接口的执行结果再加以处理。尴尬的是，当时在寻找一个可以用于快速后台接口开发的PHP框架时，没找到合适贴切的开源框架。准确来说来，没找到一个专注于接口开发的开源框架。

基于此，萌生了自主研发一个接口框架的想法。经过到图书馆和网上查阅整理多方资料、知识和理论，和一段时间的设计及编码后，便延生了最初的接口框架，并命名为： zenphpWS3。 其中，zen表示开源、php表示用PHP开发、WS表示Web Service、3表示支持SOAP、HTTP或RPC三种协议以及JSON、XML或数组等多种格式的返回。

zenphpWS3很好地支撑毕业论文项目的开发，并初步具备了一个框架的基本特质与思想。但经过一年的全职工作，以及学习、研究众多优秀开源构架后，发现还存在很多有待改善的地方。所以，当再次使用zenphpWS3进行新接口项目开发时，我便在开发具体应用接口服务的同时，也有意识地在对接口框架进行完善和重构，并融入框架所需要的特性、原则和模式。如可重用、IoC、SOILD设计原则、组件和容器等。至此，通过不断演进迭代，一个更好的接口框架便慢慢浮现了出来。

### 1.2.2 今生：开源框架PhalApi

与此同时，我们迎来了移动互联网的浪潮。很多大的企业都提供了开放平台，如腾讯开放平台、新浪微博开放平台、优酷开放平台等。而对于中型公司或者初创团队，则需要为自主的APP开发提供特定领域业务功能的接口服务。也就是说，越来越多的项目需要像我当初那样开发接口服务，但也可能同样会像我当初那样面临找不到合适贴切开源框架的困境。

秉着希望能帮助更多同学快速开发接口项目的初衷，我便再次对此接口框架进行重构优化，于2015年正式走向开源，并更名为：PhalApi，简称：π框架。

正如PHPUnit的作者Sebastian Bergmann所说的那样：

> Driven by his passion to help developers build better software.

同样，我们希望通过PhalApi，可以：

* 一来，支撑轻量级接口项目的快速开发；
* 二来，阐明如何更好地进行接口开发、设计和维护；
* 三来，分享优秀的编程思想、实用的工具和精益求精的技艺。

最初的接口框架就在这样的背景和研究下出来了。

## 1.3 接口，从简单开始！

### 1.3.1 一个隐喻

假设我们有一条这样的表达式：

```
1 + 1 = 2
```

显然，这是非常简单，且易于理解的。但倘若我们在中间添加一些复杂性后：

```
1 + (96 - 867 + 700 - 6 + 7 - 30/10 + 100  - 27) +  1 = 2
```

同样可以获得相同的结果，但表达却羞涩难懂，且容易出错。

你可能会觉得好笑：怎么可能会有人把这么简单的问题复杂化呢？还编写这么累赘的代码？然而，如果你回顾一下以往接触过的项目或留意一下身边正在运行的代码，你会发现，这种情况是真实存在的。

在不同的领域开发不同的项目，各自需求不同，所编写的代码也就不尽相同。纵使这样，即使我们不能把代码简化到最理想的状态，但至少可以通过努力以达到“编写人容易理解的代码”这一最佳状态。一如这样：

```
1 + (0) +  1 = 2
```

### 1.3.2 PhalApi框架所做的

使用PhalApi框架进行接口项目开发，我们不能保证最终编写出来的项目代码一定会“短而美”，因为更多的代码编写来自于你双手的输入、来自你自己切身的思考和设计。但我们希望PhalApi可以在支持接口快速开发的基础上，为你和更多开发团队提供关于接口项目开发的一些技艺、参考和帮助。

所以，与其他很多关注服务器性能的框架不同， PhalApi更加关注的是人的心情、开发效率和团队合作，而这些正是通过约束与规范、测试驱动开发、自动化工具、持续集成和敏捷开发等途径可以达成的 。

这一切一切，都要从代码的编写开始。毕竟我们作为专业软件开发人士，代码是我们连接世界的媒介。而接口代码的编写，又应从简单开始。

泡一杯咖啡，让我们开始吧。

## 1.4 下载与安装
可到Github下载最新版框架代码。  
> Github地址：https://github.com/phalapi/phalapi  

其中，release分支为中文稳定版；release-en分支为英文稳定版。需要使用PHP 5.3.3及以上版本。  
    
   
安装如同其他的框架一样，将下载的框架压缩包上传到服务器后解压即可。结合自己的喜爱与项目需要，可以采用Apache、XAMPP、Microsoft IIS等。根据使用的服务器不同，配置也不一样。  

本书所使用的环境是：  

 + PHP 5.3.10  
 + Nginx 1.1.19  
 + Ubuntu  
 + PhalApi 1.3.7  


所以在这里，本书统一约定使用PhaApi 1.3.7 版本，并且推荐使用Nginx作为服务器。以这里的环境安装为例，首先需要添加Nginx配置文件```/etc/nginx/sites-available/dev.phalapi.com```，并添加以下参考配置。  

代码清单1-1 /etc/nginx/sites-available/dev.phalapi.com 
```
server {
    listen 80;
    server_name dev.phalapi.com;

    root /path/to/PhalApi/Public;
    charset utf-8;

    location / {
        index index.html index.htm index.php;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    access_log logs/dev.phalapi.com.access.log;
    error_log logs/dev.phalapi.com.error.log;
}
```
  
接着创建软链：  
```
# ln -s /etc/nginx/sites-available/dev.phalapi.com /etc/nginx/sites-enabled/dev.phalapi.com
```

然后重启Nginx服务：  
```
$ /etc/init.d/nginx restart
```

接着配置本机的HOST：  
```
# vim /etc/hosts
```
并添加：  
```
127.0.0.1 dev.phalapi.com
```

最后在浏览器访问Demo的默认接口服务，测试接口是否可以正常访问，如请求：  
```
http://dev.phalapi.com/demo/
```
正常情况下，会看到类似以下有效果。  
![](https://github.com/phalapi/meet/blob/master/images/ch-1-demo-default-api.png)
图1-1 默认接口服务的响应效果  

> **温馨提示：**为了可视化JSON结果，Chrome浏览器可安装JSONView扩展，Firefox可以安装JSON-handel扩展。  

## 1.5 创建一个新项目

## 1.6 Hello World

## 1.7 对PhalApi框架的抉择

## 本章小结



