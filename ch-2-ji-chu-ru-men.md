# 第2章 基础入门  

__表达，从简单开始。——Robin Williams《写给大家看的设计书》__  
  
这一章，我们将开始学习PhalApi框架中的基础内容，包括作为客户端如何请求接口服务，作为服务端如何返回接口结果，ADM模式的含义和依赖关系，以及其他常用的基础功能。为避免内容空洞，我们会尽量结合前面的商城项目示例，进行基础内容的讲解。读者可以在边学习的过程中，边实践操作，加深理解。  

在每个小节中，我们会先学习一些基本的使用，以便能满足普遍项目开发的技术需要。对于容易误解、容易出错的地方，我们会进行温馨提示，列出注意事项以及提供正确的解决方案。在每个小节的最后，我们还会再进一步，学习如何扩展项目的能力，定制自己的功能。  


## 2.1 接口请求

PhalApi默认使用的是HTTP/HTTPS协议进行通讯，请求接口的完整URL格式则是：  
```
接口域名 + 入口路径 + ?service=Class.Action + [接口参数]
```
其中有应该单独部署的接口域名，不同项目各自的入口路径，统一约定的service参数，以及可选的接口参数。下面分别进行说明。  

### 2.1.1 接口服务URI

#### (1) 接口域名

通常，我们建议对于接口服务系统，应该单独部署一个接口域名，而不应与其他传统的Web应用系统或者管理后台混合在一起，以便分开维护。  
  
假设我们已经有一个站点，其域名为：```www.demo.com```，现需要为开发一套接口服务提供给移动App使用。直接在已有站点下添加一个入口以提供服务的做法是不推荐的，即不建议接口URI是：```www.demo.com/api```。推荐的做法是，单独配置部署一个新的接口域名，如：```api.demo.com```。当前，我们也可以发现很多公司都提供了这样独立的接口平台。例如：  

 + 优酷开放平台：https://openapi.youku.com
 + 微信公众号： https://api.weixin.qq.com
 + 新浪微博： https://api.weibo.com
  
如第1章中，我们创建的接口项目，其域名为：```api.phalapi.net```。  

#### (2) 入口路径  
入口路径是相对路径，不同的项目可以使用不同的入口。通常在这里，我们会在部署接口项目时，会把项目对外可访问的根目录设置到```./Public```目录。这里所说的入口路径都是相对这个```./Public```目录而言的。与此同时，默认使用省略```index.php```的路径写法。  
  
为了更好地理解这样的对应关系，以下是一些示例对应关系。  

项目|精简的入口路径|完整的入口路径|入口文件位置|项目源代码位置
---|---|---|---|---
默认的演示项目|/demo|/Public/demo/index.php|./Public/demo/index.php|./Demo
新建的商城项目|/shop|/Public/shop/index.php|./Public/shop/index.php|./Shop

表2-1 入口路径示例对应关系

如框架自带的演示项目，其目录是：```./Public/demo```，对应的访问入口路径是：```api.phalapi.net/demo```；而新建的商城Shop项目的目录是：```./Public/shop```，则入口路径是：```api.phalapi.net/shop```。  

这个入口路径是可选的，也可以直接使用根目录。如果是这样，则需要调整```./Public/index.php```目录，并且不便于多项目并存的情况。    

#### (3) 指定接口服务
在PhalApi中，我们统一约定使用```service```参数来指定所请求的接口服务。通常情况下，此参数使用GET方式传递，即使用```$_GET['service']```，其格式为：```?service=Class.Action```。其中```Class```是对应请求的接口剔除Api_前缀后的类名，```Action```则是待执行的接口类中的方法名。

> 温馨提示：未指定service参数时，默认使用```?service=Default.Index```。  
  
如请求默认的接口服务可用```?service=Default.Index```，则相应会调用```Api_Default::Index()```这一接口服务；若请求的是```?service=Welcome.Say```，则会调用```Api_Welcome::Say```这一接口服务。  
  
以下是一些示例。  

 + 请求默认接口服务，省略service
```
http://api.phalapi.net/shop/  
```

 + 等效于请求默认接口服务
```
http://api.phalapi.net/shop/?service=Default.Index  
```
  
 + 请求Hello World接口服务
```
http://api.phalapi.net/shop/?service=Welcome.Say
```

#### (4) 接口参数  
接口参数是可选的，根据不同的接口服务所约定的参数进行传递。可以是GET参数，POST参数，或者多媒体数据。未定制的情况下，PhalApi既支持GET参数又支持POST参数。   

如使用GET方式传递username参数：
```
$ curl "http://api.phalapi.net/shop/?service=Default.Index&username=dogstar"
```
也可以用POST方式传递username参数：
```
$ curl -d "username=dogstar" "http://api.phalapi.net/shop/?service=Default.Index"
```

至此，我们已经基本了解如何对接口服务发起请求。接下来，让我们来看下对于接口服务至关重要的要素 —— 接口参数。    

### 2.1.2 参数规则
接口参数，对于接口服务本身来说，是非常重要的。对于外部调用的客户端来说，同等重要。对于接口参数，我们希望能够既减轻后台开发对接口参数获取、判断、验证、文档编写的痛苦；又能方便客户端快速调用，明确参数的意义。由此，我们引入了**参数规则**这一概念，即：通过配置参数的规则，自动实现对参数的获取和验证，同时自动生成在线接口文档。  
  
参数规则是针对各个接口服务而配置的多维规则数组，由```PhalApi_Api::getRules()```方法返回。其中，参数规则数组的一维下标是接口类的方法名，对应接口服务的Action；二维下标是类属性名称，对应在服务端获取通过验证和转换化的最终客户端参数；三维下标```name```是接口参数名称，对应外部客户端请求时需要提供的参数名称。即：  
```
    public function getRules() {
        return array(
            '接口类方法名' => array(
                '接口类属性' => array('name' => '接口参数名称', ... ... ),
            ),
        );
    }
```  

通常情况下，接口类属性和接口参数名称一样，但也可以不一样。一种情况是客户端的接口参数名称惯用下划线分割，即蛇形(下划线)命名法，而服务端中则惯用驼峰命名法。例如对于“是否记住我”，客户端参数用```is_remember_me```，服务端用```isRememberMe```。另一种情况是如果参数名称较长，为了节省移动网络下的流量，也可以针对客户端参数使用有意义的缩写。如前面的“是否记住我”客户端缩写成```is_rem_me```。   
  
在参数规则里，可以配置多个接口类方法名，每个方法名的规则，又可以配置多个接口类属性，即有多个接口参数。  
  
配置好参数规则后，当接口参数通过验证后，就可以在接口类方法内，通过类成员属性获取相应的接口参数。  

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
> 温馨提示：当接口参数非法时，返回的ret都为400，且data为空。在这一章节中，当再次非法返回时，将省略ret与data，以节省篇幅。

#### (3) 三级参数规则配置
参数规则主要有三种，分别是：系统参数规则、应用参数规则、接口参数规则。  

系统参数是指被框架保留使用的参数。目前已被PhalApi占用的系统参数只有一个，即：service参数。类型为字符串，格式为：Class.Action，首字母不区分大小写，建议统一以大写开头。  

以下是一些示例： 

 + 推荐写法，类名和方法名开头大写
```
?service=User.Login
```

 + 正确写法，类名和方法名开头都小写，或方法名全部小写
```
?service=user.login
?service=user.getbaseinfo
```

 + 错误写法，缺少方法名、缺少点号分割、使用竖线而非点号分割
```
?service=User
?service=UserLogin
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
        //客户端App版本号，默认为：1.4.0
        'version' => array(
            'name' => 'version', 'default' => '1.4.0', 
        ),
    ),

    ... ...
```
其配置格式和前面所说的接口参数规则配置类似，都是一个规则数组。区别是这里是二维数组，相当于全部方法的公共的接口类属性。    

接口参数是指各个具体的接口服务所需要的参数，为特定的接口服务所持有，独立配置。并且进一步在内部又细分为两种：  

 + **通用接口参数规则**：使用```*```作为下标，对当前接口类全部的方法有效。  
 + **指定接口参数规则**：使用方法名作为下标，只对接口类的特定某个方法有效。  


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
 + 4、系统参数规则（通常忽略，当前只有service）

#### (5) 参数规则配置
具体的参数规则，根据不同的类型有不同的配置选项，以及一些公共的配置选项。目前，主要的类型有：字符串、整数、浮点数、布尔值、时间戳/日期、数组、枚举类型、文件上传和回调函数。    

类型 type|参数名称 name|是否必须 require|默认值 default|最小值 min，最大值 max|更多配置选项（无特殊说明，均为可选）
---|---|---|---|---|---
字符串|string|TRUE/FALSE，默认FALSE|应为字符串|可选|regex选项用于配置正则匹配的规则；format选项用于定义字符编码的类型，如utf8、gbk、gb2312等
整数|int|TRUE/FALSE，默认FALSE|应为整数|可选|---
浮点数|float|TRUE/FALSE，默认FALSE|应为浮点数|可选|---
布尔值|boolean|TRUE/FALSE，默认FALSE|true/false|---|以下值会转换为TRUE：ok，true，success，on，yes，1，以及其他PHP作为TRUE的值
时间戳/日期|date|TRUE/FALSE，默认FALSE|日期字符串|可选，仅当为format配置为timestamp时才判断，且最值应为时间戳|format选项用于配置格式，为timestamp时会将字符串的日期转换为时间戳
数组|array|TRUE/FALSE，默认FALSE|字符串或者数组，为非数组会自动转换/解析成数组|可选，判断数组元素个数|format选项用于配置数组和格式，为explode时根据separator选项将字符串分割成数组, 为json时进行JSON解析
枚举|enum|TRUE/FALSE，默认FALSE|应为range选项中的某个元素|---|必须的range选项，为一数组，用于指定枚举的集合
文件|file|TRUE/FALSE，默认FALSE|数组类型|可选，用于表示文件大小范围，单位为B|range选项用于指定可允许上传的文件类型；ext选项用于表示需要过滤的文件扩展名
回调|callable/callback|TRUE/FALSE，默认FALSE|---|---|callable/callback选项用于设置回调函数，params选项为回调函数的第三个参数（另外第一个为参数值，第二个为所配置的规则）  

表2-2 参数规则选项一览表  

##### 公共配置选项

公共的配置选项，除了上面的类型、参数名称、是否必须、默认值，还有说明描述、数据来源。下面分别简单说明。  
 
 + **类型 type**  
 用于指定参数的类型，可以是string、int、float、boolean、date、array、enum、file、callable，或者自定义的类型。未指定时，默认为字符串。  
  
 + **参数名称 name**  
 接口参数名称，即客户端需要传递的参数名称。与PHP变量规则一样，以下划线或字母开头。此选项必须提供，否则会提示错误。   
  
 + **是否必须require**  
 为TRUE时，表示此参数为必须值；为FALSE时，表示此参数为可选。未指定时，默认为FALSE。  
  
 + **默认值 default**  
 未提供接口参数时的默认值。未指定时，默认为NULL。  
  
 + **最小值 min，最大值 max**  
 部分类型适用。用于指定接口参数的范围，比较时采用的是闭区间，即范围应该为：[min, max]。也可以只使用min或max，若只配置了min，则表示：[min, +∞)；若只配置了maz，则表示：(-∞, max]。   

 + **说明描述 desc**  
 用于自动生成在线接口详情文档，对参数的含义和要求进行扼要说明。未指定时，默认为空字符串。  
  
 + **数据来源 source**  
 指定当前单个参数的数据来源，可以是post、get、cookie、server、request、header、或其他自定义来源。未指定时，默认为统一数据源。目前支持的source与对应的数据源映射关系如下：  

source|对应的数据源  
---|---
post     | $_POST              
get      | $_GET               
cookie   | $_COOKIE            
server   | $_SERVER            
request  | $_REQUEST           
header   | $_SERVER['HTTP_X']  

表2-3 source与对应的数据源映射关系  
  
##### 9种参数类型

对于各种参数类型，结合示例说明如下。  

 + **字符串 string**  

当一个参数规则未指定类型时，默认为string。如最简单的：  
```
array('name' => 'username')
```
> 温馨提示：这一小节的参数规则配置示例，都省略了类属性，以关注配置本身的内容。  

这样就配置了一个参数规则，接口参数名字叫username，类型为字符串。  

一个完整的写法可以为：
```
array('name' => 'username', 'type' => 'string', 'require' => true, 'default' => 'nobody', 'min' => 1, 'max' => 10)
```
这里指定了为必选参数，默认值为nobody，且最小长度为1个字符，最大长度为10个字符，若传递的参数长度过长，如```&username=alonglonglonglongname```，则会异常失败返回：
```
"msg": "非法请求：username.len应该小于等于10, 但现在username.len = 21"
```
  

当需要验证的是中文的话，由于一个中文字符会占用3个字节。所以在min和max验证的时候会出现一些问题。为此，PhalApi提供了format配置选项，用于指定字符集。如：  

```
array('name' => 'username', 'type' => 'string', 'format' => 'utf8', 'min' => 1, 'max' => 10)
```
  
我们还可以使用```regex```下标来进行正则表达式的验证，一个邮箱的例子是：  
```
array('name' => 'email', 'regex' => "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i")
```

 + **整型 int**  

整型即自然数，包括正数、0和负数。如通常数据库中的id，即可配置成：  
```
array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1)
```

当传递的参数，不在其配置的范围内时，如```&id=0```，则会异常失败返回：
```
"msg": "非法请求：id应该大于或等于1, 但现在id = 0"
```

另外，对于常见的分页参数，可以这样配置：  
```
array('name' => 'page_num', 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 20)
```
即每页数量最小1个，最大20个，默认20个。  


 + **浮点 float**  

浮点型，类似整型的配置，此处略。 

 + **布尔值 boolean**  

布尔值，主要是可以对一些字符串转换成布尔值，如ok, true, success, on, yes, 以及会被PHP解析成true的字符串，都会转换成TRUE。如通常的“是否记住我”参数，可配置成：
```
array('name' => 'is_remember_me', 'type' => 'boolean', 'default' => TRUE)
```
  
则以下参数，最终服务端会作为TRUE接收。  
```
?is_remember_me=ok
?is_remember_me=true
?is_remember_me=success
?is_remember_me=on
?is_remember_me=yes
?is_remember_me=1
```

 + **日期 date**  

日期可以按自己约定的格式传递，默认是作为字符串，此时不支持范围检测。例如配置注册时间：
```
array('name' => 'register_date', 'type' => 'date')
```
对应地，```register_date=2015-01-31 10:00:00```则会被获取到为："2015-01-31 10:00:00"。
  
当需要将字符串的日期转换成时间戳时，可追加配置选项```'format' => 'timestamp'```，则配置成：
```
array('name' => 'register_date', 'type' => 'date', 'format' => 'timestamp')
```
则上面的参数再请求时，则会被转换成：1422669600。  

此时作为时间戳，还可以添加范围检测，如限制时间范围在31号当天：  
```
array('name' => 'register_date', 'type' => 'date', 'format' => 'timestamp', 'min' =>  1422633600, 'max' => 1422719999)
```

当配置的最小值或最大值为字符串的日期时，会自动先转换成时间戳再进行检测比较。如可以配置成：  
```
array('name' => 'register_date', ... ... 'min' => '2015-01-31 00:00:00', 'max' => '2015-01-31 23:59:59')
```

 + **数组 array**  

很多时候在接口进行批量获取时，都需要提供一组参数，如多个ID，多个选项。这时可以使用数组来进行配置。如：  
```
array('name' => 'uids', 'type' => 'array', 'format' => 'explode', 'separator' => ',')
```

这时接口参数```&uids=1,2,3```则会被转换成：  
```
array ( 0 => '1', 1 => '2', 2 => '3', )
```

如果设置了默认值，那么默认值会从字符串，根据相应的format格式进行自动转换。如：  
```
array( ... ... 'default' => '4,5,6')
```
那么在未传参数的情况下，自动会得到：  
```
array ( 0 => '4', 1 => '5', 2 => '6', )
```

又如接口需要使用JSON来传递整块参数时，可以这样配置：
```
array('name' => 'params', 'type' => 'array', 'format' => 'json')
```
对应地，接口参数```&params={"username":"test","password":"123456"}```则会被转换成：
```
array ( 'username' => 'test', 'password' => '123456', )
```
> 温馨提示：使用JSON传递参数时，建议使用POST方式传递。若使用GET方式，须注意参数长度不应超过浏览器最大限制长度，以及URL编码问。  

若使用JSON格式时，设置了默认值为：  
```
array( ... ... 'default' => '{"username":"dogstar","password":"xxxxxx"}')
```
那么在未传参数的情况下，会得到转换后的：  
```
array ( 'username' => 'dogstar', 'password' => 'xxxxxx', )
```

特别地，当配置成了数组却未指定格式format时，接口参数会转换成只有一个元素的数组，如接口参数：```&name=test```，会转换成：
```
array ( 0 => 'test' )
```


 + **枚举 enum**  

在需要对接口参数进行范围限制时，可以使用此枚举型。如对于性别的参数，可以这样配置：
```
array('name' => 'sex', 'type' => 'enum', 'range' => array('female', 'male'))
```
当传递的参数不合法时，如```&sex=unknow```，则会被拦截，返回失败：
```
"msg": "非法请求：参数sex应该为：female/male，但现在sex = unknow"
```
  
关于枚举类型的配置，这里需要特别注意配置时，应尽量使用字符串的值。  
因为通常而言，接口通过GET/POST方式获取到的参数都是字符串的，而如果配置规则时指定范围用了整型，会导致底层规则验证时误判。例如接口参数为```&type=N```，而接口参数规则为：  
```
array('name' => 'type', 'type' => 'enum', 'range' => array(0, 1, 2))
```
则会出现以下这样的误判：  
```  
var_dump(in_array('N', array(0, 1, 2))); // 结果为true，因为 'N' == 0
```  
  
为了避免这类情况发生，应该使用使用字符串配置范围值，即可这样配置：  
```
array('name' => '&type', 'type' => 'enum', 'range' => array(`0`, `1`, `2`))
```
  
 + **文件 file**  

在需要对上传的文件进行过滤、接收和处理时，可以使用文件类型，如：
```
array(
    'name' => 'upfile', 
    'type' => 'file', 
    'min' => 0, 
    'max' => 1024 * 1024, 
    'range' => array('image/jpeg', 'image/png') , 
    'ext' => array('jpeg', 'png')
)
```
其中，min和max分别对应文件大小的范围，单位为字节；range为允许的文件类型，使用数组配置，且不区分大小写。 
  
如果成功，返回的值对应的是```$_FILES["upfile"]```，即会返回：
```
array(
     'name' => ..., // 被上传文件的名称
     'type' => ..., // 被上传文件的类型
     'size' => ..., // 被上传文件的大小，以字节计
     'tmp_name' => ..., // 存储在服务器的文件的临时副本的名称
)
```
对应的是：  
 + $_FILES["upfile"]["name"] - 被上传文件的名称
 + $_FILES["upfile"]["type"] - 被上传文件的类型
 + $_FILES["upfile"]["size"] - 被上传文件的大小，以字节计
 + $_FILES["upfile"]["tmp_name"] - 存储在服务器的文件的临时副本的名称
 + $_FILES["upfile"]["error"] - 由文件上传导致的错误代码
  

若需要配置默认值default选项，则也应为一数组，且其格式应类似如上。

其中，ext是对文件后缀名进行验证，当如果上传文件后缀名不匹配时将抛出异常。文件扩展名的过滤可以类似这样进行配置：

+ 单个后缀名 - 数组形式  
```
'ext' => array('jpg')
```

 + 单个后缀名 - 字符串形式  
```
'ext' => 'jpg'
```

 + 多个后缀名 - 数组形式  
```
'ext' => array('jpg', 'jpeg', 'png', 'bmp')
```

 + 多个后缀名 - 字符串形式（以英文逗号分割）  

```
'ext' => 'jpg,jpeg,png,bmp' 
```

> 温馨提示：文件上传时请使用表单上传，并enctype 属性使用"multipart/form-data"。具体请参考：[PHP 文件上传](http://www.w3school.com.cn/php/php_file_upload.asp)  
  
 + **回调 callable/callback**  
当需要利用已有函数进行自定义验证时，可采用回调参数规则，如配置规则：  

```
array('name' => 'version', 'type' => 'callable', 'callback' => array('Common_Request_Version', 'formatVersion'))
```
然后，回调时将调用下面这个新增的类函数：
```
// $ vim ./Shop/Common/Request/Version.php
<?php
class Common_Request_Version {

    public static function formatVersion($value, $rule) {
        if (count(explode('.', $value)) < 3) {
            throw new PhalApi_Exception_BadRequest('版本号格式错误');
        }
        return $value;
    }
}
```

> 温馨提示：回调函数的签名为：```function format($value, $rule, $params)```，第一个为参数原始值，第二个为所配置的规则，第三个可选参数为配置规则中的params选项。最后应返回转换后的参数值。  
  
还记得我们前面刚学的应用参数规则吗？在那里我们配置了一个version参数，现在让我们把这个版本参数类型修改成此自定义回调类型。即：  
```
// $ vim ./Config/app.php
        ... ...
        'version' => array(
            // 'name' => 'version', 'default' => '1.4.0', , 
            'name' => 'version', 'type' => 'callable', 'callback' => array('Common_Request_Version', 'formatVersion'), 'default' => '1.4.0'
        )
```
修改好后，便可使用此自定义的回调处理了。  
当正常传递合法version参数，如请求```/shop/welcome/say?version=1.2.3```，可以正常响应。若故意传递非法的version参数，如请求```/shop/welcome/say?version=123```，则会提示这样的错误：  
```
"msg": "非法请求：版本号格式错误"
```
  
由于自 PHP 5.4 起可用callable类型指定回调类型callback。所以，为了减轻记忆的负担，这里使用callable或者callback来表示类型都可以，即可以配置成：```'type' => 'callable',```，也可以配置成：```'type' => 'callback',```。回调函数的选项也一样。  

  
以下是来自PHP官网的一些回调函数的示例：  
```
// Type 1: Simple callback
call_user_func('my_callback_function'); 

// Type 2: Static class method call
call_user_func(array('MyClass', 'myCallbackMethod')); 

// Type 3: Object method call
$obj = new MyClass();
call_user_func(array($obj, 'myCallbackMethod'));

// Type 4: Static class method call (As of PHP 5.2.3)
call_user_func('MyClass::myCallbackMethod');
```
> 参考：更多请参考[Callback / Callable 类型](http://php.net/manual/zh/language.types.callable.php)。  

所以上面的callback也可以配置成：  
```
'callback' => 'Common_Request_Version::formatVersion'
```


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

实现好自定义的请求类后，需要在项目的入口文件```./Public/shop/index.php```进行注册。  
```
// $ vim ./Public/shop/index.php
//装载你的接口
DI()->loader->addDirs('Shop');

DI()->request = new Common_Request_Ch1();
```

这样，除了原来的请求方式，还可以这样请求接口服务。  


原来的方式|现在的方式
---|---
/shop/?service=Default.Index|?r=Default/Index   
/shop/?service=Welcome.Say|?r=Welcome.Say   

表2- 使用?r=Class/Action格式定制后的方式

这里有几个注意事项： 

 + 1、重载后的方法需要转换为原始的接口服务格式，即：Class.Action  
 + 2、为保持兼容性，子类需兼容父类的实现。即在取不到自定义的接口服务名称参数时，应该返回原来的接口服务。  

除了在框架编写代码实现其他接口服务的传递方式外，还可以通过Web服务器的规则Rewirte来实现。假设使用的是Nginx服务器，那么可以添加以下Rewrite配置。  
```
    if ( !-f $request_filename )
    {
        rewrite ^/shop/(.*)/(.*) /shop/?service=$1.$2;
    }
```
重启Nginx后，便可得到以下这样的效果。  

原来的方式|现在的方式
---|---
/shop/?service=Default.Index|/shop/default/index   
/shop/?service=Welcome.Say|/shop/welcome/say  
  
表2- 使用Nginx服务器Rewrite规则定制后的方式

此外，还有第三种指定传递方式的方案。使用第三方路由规则类库，然后通过简单的项目配置，从而实现更复杂、更丰富的规则定制。这部分后面会再进行讨论。  

小结一下，不管是哪种定制方式，最终都是转换为框架最初约定的方式，即：```?service=Class/Action```。  

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