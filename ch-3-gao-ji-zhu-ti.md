# 第3章 高级主题

__设计软件有两种方法：一种是简单到明显没有缺陷，另外一种复杂到缺陷不那么明显。——托尼.霍尔__   

在学习了前面第2章的基础入门后，已经可以能够胜任一般性的接口服务功能开发，和应付根据项目具体情况而定制扩展的业务场景。但在更高层面，对于为什么需要这样实现，以及各种开发实现背后所蕴含的理念、规范和原理，则需要通过这一章的学习来获得。在这一章，我们将会找到能更好指导进行恰如其分接口开发的抽象思想。例如对各种后端资源服务的管理，类文件的命名规范和自动加载，以及架构明显的编程风格。除此之外，高级主题还会包含更高层面、更广范畴上对PhalApi的使用讲解，例如脚本命令的使用、扩展类库的介绍和接口查询语言及SDK包等，而不再像是基础入门那样只限于某个类的使用。   

## 3.1 让资源更可控的依赖注入

伟大的毛主席曾言，“不到长城非好汉，屈指行程二万。”而对于使用和学习PhalApi的时，如果不了解或掌握其中的依赖注入，就不能称得上是优秀的PhalApi开发人员。因为依赖注入在PhalApi中扮演着重要、不可或缺的角色，可以说依赖注入是PhalApi中的“一等公民”。  

### 3.1.1 何为依赖注入？

依赖注入，即Dependency Injection，简称DI，属于控制反转的一种类型，目的是了减少耦合性，简单来说就是使用开放式来初始化、管理和维护资源。这里说的资源主要是在后端所使用到的资源或组件，包括但不限于配置、数据库、高效缓存、接口请求与响应以及项目级的实例。而这些资源通常会存在依赖关系，如果处理不当，则会引发混乱，甚至循环依赖。  

让我们来看一些简单例子，感受下使用依赖注入降低软件开发复杂性的快感。假设A类依赖于B类，则可以这样实现：    
```
<?php
class A {
    protected $b;
 
    public function __construct() {
        $this->b = new B();      
    }
}
```
这种方式在A内限制约束了B的实例对象，当改用B的子类或者改变B的构建方式时，A需要作出调整。这时可以通过依赖来改善这种关系。  
```
<?php
class A {
    protected $b;
 
    public function __construct($b) {
        $this->b = $b;       
    }
}
```

再进一步，可以使用依赖注入对B对象乃至全部的资源进行统一管理。  
```
<?php
class A {
    public function __construct() {       
    }
 
    public function doSth() {
        // 在需要使用B的地方
        $b = DI()->get('B');
    }
}
```
其中，```DI()```快速函数将会返回一个依赖注入式的管理容器实例，下面会详细讲到。  

这样的好处是什么呢？一方面，对于使用A的客户（这里指的是开发人员），不需要再添加一个B的成员变量，特别不是全部类的成员函数都需要使用B类服务时；另一方面在外部多次初始化A实例时，可以统一对B的实例进行构建。  

依赖注入在PhalApi中扮演着重要、不可或缺的角色，可以说依赖注入是PhalApi中的“一等公民”。在过去的框架中，可能会使用无处不在的全局变量维护、管理各种资源，而在PhalApi中，则是使用定义良好、规范的容器统一管理。在下面PhalApi框架UML静态类结构中，也可以明显看出DI所处于的重要的位置和扮演的角色。    
![](images/ch-3-uml-di.jpg)  
图3-1 DI在PhalApi静态类结构中的位置

### 3.1.2 依赖注入的基本使用

如前面所言，使用```DI()```快速函数可以获取一个依赖注入式的管理容器实例，其用法等效于获取PhalApi_DI单例的```PhalApi_DI::one()```静态方法。后文中，我们将统一称此容器实例简称为DI。DI的使用主要是对资源服务进行注册与获取，调用的方式有：set/get方法、setter/getter访问器、类成员属性、数组形式，初始化的途径有：直接赋值、类名延迟加载、匿名函数延迟加载。  

假设已有如下演示类。  
```
<?php
class Simple {
    public function __construct() {
    }
}
```

 + **set/get方法**  

在对DI进行设置和获取资源时，可以使用set/get方法，即：```PhalApi_DI::set($key, $value)```和```PhalApi_DI::get($key, $default = NULL)```方法。下面是使用示例：  
```
// 直接赋值
DI()->set('aString', 'Hello Dependency Injection!');

// 使用类名延迟加载
DI()->set('aObject', 'Simple');

// 使用匿名函数延迟加载
DI()->set('aClosure', function(){
    return new Simple();
});

// 获取
var_dump(DI()->get('aString'));
var_dump(DI()->get('aObject'));
var_dump(DI()->get('aClosure'));
```

 ```PhalApi_DI::get($key, $default = NULL)```方法的第二个参数为默认值，当资源未注册时将返回此默认值。例如： 
```
// 输出默认值 2017
echo DI()->get('aInt', 2017);
```

 + **setter/getter访问器**  

也可以使用setter/getter访问器对资源进行设置与获取，这时资源名称的首写字母需要大写。上面示例，改用访问器，等效实现如下。   
```
// 直接赋值
DI()->setAString('Hello Dependency Injection!');

// 使用类名延迟加载
DI()->setAObject('Simple');

// 使用匿名函数延迟加载
DI()->setAClosure(function(){
    return new Simple();
});

// 获取
var_dump(DI()->getAString());
var_dump(DI()->getAObject());
var_dump(DI()->getAClosure());
```

在使用getter时，第一个参数为默认值，即当资源未注册时将返回此默认值。例如： 
```
// 输出默认值 2017
echo DI()->getAInt(2017);
```

 + **类成员属性**  

我们还可以通过类成员属性的方式对资源进行设置与获取，这时资源名称需要符合PHP的变量命名要求，即应该以下划线或字母开头，由数字、字母、下划线组成。上面示例，改用类成员属性方式，等效实现如下：  
```
// 直接赋值
DI()->aString = 'Hello Dependency Injection!';

// 使用类名延迟加载
DI()->aObject = 'Simple';

// 使用匿名函数延迟加载
DI()->aClosure = function(){
    return new Simple();
};

// 获取
var_dump(DI()->aString);
var_dump(DI()->aObject);
var_dump(DI()->aClosure);
```

使用这种类成员属性的方式获取资源时，不能指定默认值。并且可以看到PhalApi大部分情况下也是使用了这种方式，例如在初始化文件./Public/init.php对各种资源的初始和注册。但当需要指定默认值时，则需要使用前面set/get方法，或setter/getter访问器。  

 + **数组形式**  

对DI设置和获取资源，还有一种方式就是使用数组形式。这时，为了更好地演示，需要使用一个临时变量```$di```来存放DI实例。前面示例的等效实现如下：  
```
$di = DI();

// 直接赋值
$di['aString'] = 'Hello Dependency Injection!';

// 使用类名延迟加载
$di['aObject'] = 'Simple';

// 使用匿名函数延迟加载
$di['aClosure'] = function(){
    return new Simple();
};

// 获取
var_dump($di['aString']);
var_dump($di['aObject']);
var_dump($di['aClosure']);
```

如同类成员属性的方式一样，此数组形式也不能指定默认值。  
  
可以看到，对于设置和获取资源，我们可以根据自己的喜爱而选择不同的操作方式。但这四种操作方式之间又有一些微妙的区别，例如类成员属性和数组形式不支持指定默认值，使用类成员属性和setter访问器时不能使用非法的变量名称。通过类名的方式来进行延迟加载，需要等初始化的类提供public访问级别无参数的构造函数。如果还有其他需要初始化的工作，可以放置在onInitialize()函数成员内，DI会在对类实例化时自动触发此函数的调用。  

### 3.1.3 架构明显的编程风格

#### (1) 开发-配置-使用模式

在基础入门的各个章节最后，我们都可以看到“扩展你的项目”这样的内容，是因为通过DI统一管理和维护资源外，便可以使用**开发-配置-使用模式**轻松对资源进行替换、升级。开发-配置-使用 模式即：开发实现-配置注册-客户使用模式。此模式能够有效解决框架固有功能与日益多样化项目开发需求之间的鸿沟。  

 + **开发实现**  

开发实现主要是指实现组件、公共服务或者基础设施的功能，此部分通常由者有经验的开发工程师来完成。  

例如对项目的接口签名的验证拦截、一个完成了对七牛云存储接口调用的扩展、又或者是项目内部加密的方案等，这些以包或者接口提供，为外部使用提供配套的配置说明、使用示例和文档说明。更为重要的是，应该提供完善、具备自我验证能力、高代码覆盖率的单元测试，以保证实现功能的稳定性。此类实现应该是稳定的，即没有明显或者隐藏的BUG。即使有，原作者也可以快速进行定位和解决，包括后期的扩展和升级也是。  

如果实现的是PhalApi框架中的功能，则应该实现对应的接口，如：加解密接口、日志接口、缓存接口等。这样的示例，在前面讲解基础内容时已遇到了很多。这里再来稍微回顾一下其中的数据库日志示例。   
```
<?php
class Common_Logger_DB extends PhalApi_Logger {
    
    public function log($type, $msg, $data) {
        // TODO 数据库的日志写入 ...
    } 
}
```
具体实现类，通过可以放置在项目的Common目录下，也可以根据自身项目的情况放置到任意地方。如果是功能更丰富的包，则可以考虑放到扩展类库目录下。  

 + **配置注册**  

一旦上面实现好具体的功能后，不同的项目都可以轻松引入然后使用了。这块通常由项目的负责人来操作，因为在进行项目构建部署时，需要考虑哪些资源是必须的，这些资源又应以何种方式进行初始化和装配。打个比方，前面我们已经准备好了很多原材料，很多工具，但在开始构建一个房子时，还需要请项目负责人把这些材料和工具有效地结合安排起来，以便体现它们的最大价值。  

这里的使用方式，应该是简明的，包括简明的安装，简明的配置。所以，自然而言，就涉及到了依赖注入。通过DI，项目负责人，可以轻松地将已通过严格测试的组件或服务注册进来。完成此步骤后，一切都整装待发，剩下的就是如何使用的问题了。  

如使用上面的数据库日志重新注册```DI()->logger```服务。  
```
DI()->logger = new Common_Logger_DB(
    PhalApi_Logger::LOG_LEVEL_DEBUG | PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);
```

 + **客户使用**  

项目总会有不断变化的新需求，而团队也会因此同步增加吸纳新开发同学进来负责新模块新功能的开发。而对于新来的同学，往往需要使用已有的功能，以便快速实现具体的业务逻辑、规则和功能。但如果他们需要重复实现这些基础重要的功能，还要考虑如何与现在项目整合，则会过多分散他们的关注点。此外，即使很好地实现了，也会常常因为考虑不周或者编程风格各异而产出一些与项目期望不符的代码，惭而产生更多的熵。  

若换一种工作的方式，即如果新来的项目成员使用已有的组件进行一些特定领域业务的开发，会是怎样？我想，情况应该会大有改观。比如，新来的项目成员，使用```DI()->logger```就可以写一条日志了。  
```
DI()->logger->debug('app enter');
```

新手总是喜欢追问一些问题，他们可能会问到，怎样才能将一些参数（当时日志的上下文）也进行纪录呢？你可以很骄傲地说：也是可以的，你只需要这样写就可以了.  
```
DI()->logger->debug('app enter', array('device' => 'iOS', 'version' => '1.1.0'));
```

特别地，当需要把日志纪录从文件存储切换到其他存储媒介，如数据库时，这对原来的使用是无影响的。而且，新手在感知不到的情况下，就能轻松实现切换了。  

#### (2) 创建和使用分离

开发-配置-使用模式也符合了创建和使用分离的思想。不同的项目，不同的应用，需要的初始化服务不一样；不同的规模，对不同的技术解决方案也不一样；不同的环境，配置也不一样。即便这样，新手还是可以一如既往地使用之前注册的服务，也就是不需要修改任何调用代码。也就是说，底层的调整或者环境的变更，对新手的使用都是透明的。为了更好地理解这些概念，这里补充一些案例场景。  

继续以我们熟悉的日志使用为例。假设我们有个项目A，分别部署到内网测试环境和外网生产环境，显然内外网环境的配置是不一样的。我们希望在内网环境为日志开启debug模式以方便开发人员进行调试，在外网则希望将其关闭以减少系统的性能开销。在一开始使用文件作为日志存储方案时，对应的内网环境初始化代码如下： 
```
// 日志纪录
DI()->logger = new PhalApi_Logger_File(API_ROOT . '/Runtime', 
    PhalApi_Logger::LOG_LEVEL_DEBUG | PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);
```

在外网环境中，只需要去掉PhalApi_Logger::LOG_LEVEL_DEBUG即可： 
```
// 日志纪录
DI()->logger = new PhalApi_Logger_File(API_ROOT . '/Runtime', 
    PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);
```

随着项目的不断发展，我们有了一批又一批的新用户。产品经理为此很开心，也请我们开发吃了好几顿大餐。但谨慎的我们发现了现在文件日记的一些限制。如即时文件读写带来了I/O瓶颈，而且不能将分布式的日记文件自动收集起来。所以，我们决定对logger进行更深层次的探索……  

至于最后是使用了Hive还是Hadoop，还是异步后台队列的方式实现，我们这里不具体指定。假设全新的智能logger研发成功后，我们便可以轻松对原有的文件日记组件进行升级，实现完美切换：  
```
// 升级后的日记纪录
DI()->logger = new Common_Logger_Smart(PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);
```
这不仅是几行代码上的区别，而是针对不同问题不同技术解决方案的抉择。这也是有经验的开发和新手之间的区别，因为你选择的技术解决方案要和面临的风险相匹配。例如用牛刀来杀鸡，就是一个不匹配的做法，就如同使用高级的Hive来实现单一小项目的日记存储一样。   

这是令人值得兴奋的。在很多遗留项目里面，当遇到瓶颈时，会请一些外部的专家来指导或优化。但即使拥有着各种“法宝”以及知道何时该使用哪种方案的专家，对于这种残留的代码也会步履维艰基于束手无策。因为，各种初始化和调用的代码，分遍在项目的“全国各地，四面八方”。即使你优化了，你会发现还要手动一个个地进行切换升级。更重要的是，很多时候不是你想优化就能优化的，即会受限于已有的上下文场景。  

我曾经遇到过这样一个遗留系统。它是在UcHome基础上而进行的二次开发，但对于它的数据库使用，开发人员没有过多地优化，如：没有使用缓存，没有进行批量合并查询优化，重复查询相同的数据，没有建立索引，等等。这样的后果就是，请求一次接口，会触发150条到500条SQL语句不等。后来我在底层添加了在线查看调试SQL语句的功能，尝试进行了一些合并查询，但当我想为数据库的表添加索引时，发现它用的却是虚拟表视图！

#### (3) 扩展类库对“开发-配置-使用”模式的应用

如果说DI是微观上对“开发-配置-使用”模式的使用，那么PhalApi的扩展类库则是宏观上对此模式的应用。PhalApi扩展类库也是由第三方开发实现的，可能是PhalApi开发团队、项目的其他成员或者你自己，然后再通过简单配置或者无配置，就可以使用扩展类库的功能了。例如邮件发送、Ecxel的操作诸如此类。之所以提供扩展类库的形式，是因为DI资源更适合于单个类以及几个操作接口，而扩展类库则提供更丰富的功能操作和一系列的接口。这样以后，项目就可以简单快速共享已有的扩展类库。难道这不是一件令人兴奋的事情吗？毕竟“哈啊！我又找到了一个可以直接用的代码类库”，要比“唉，又要写一堆重复的代码，还要测试、联调……”更能让人心情愉悦。   

#### (4) 回顾Yii框架的发现

程序、系统和框架，其作用太多数都体现在动态的功能上，而不是静态有限的功能。而动态的功能则很大程序上依赖于各种配置，如Tomcat下各层级xml配置。有些框架对配置这块提供了丰富的支持，但为此的代码是，配置难以掌控。以Yii框架为例（Yii是一个很优秀的框架，这里只是以事论事），当你需要在视图渲染一个数据表格时，你可以使用CGridView，并类似这样配置： 

```
$columns = array(
    array('name' => 'mId', 'header' => '序号'),
    array('name'=>'id', 'header'=>'事件ID'),
    array('name'=>'title', 'header'=>'标题'),
    array('name'=>'content', 'header'=>'内容', 'type' => 'html'),
);

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$dataProvider,
    'columns'=> $columns,
));
```

更为复杂的情况可以是：
```
$columns = array(
    // ... ...
    array('class' => 'CDataColumn', 'header' => '内容', 'type' => 'html', 'name' => 'content', 'htmlOptions' => array('width' => '200px')),
    array(
        'class'=>'CButtonColumn',
        'template'=>'{showEvent}<br/>{deleteEvent}',
        'header'=>'操作',
        'buttons'=>array
        (
            'showEvent' => array(
                'label' => '查看',
                'url' => '"?r=DailyOperations/eventManagerShow&user_iduser_id=' . $userId  . '&eventId=". $data["id"];',
                'options' => array('target' => '_blank'),
            ),
            'deleteEvent' => array(
                'label'=>'删除',
                'url'=>'"javascript:void(0)"',
                'imageUrl'=>'/images/delete_24.png',
                'deleteConfirmation'=>"js:'Record with ID '+$(this).parent().parent().children(':first-child').text()+' will be deleted! Continue?'",
                'click'=>'js:function(){if (confirm("此操作将删除：ID = " + $(this).parent().parent().children(\':first-child\').text() + " \n是否确定？")) {deleteEvent($(
this).parent().parent().children(\':first-child\').text());};}',
            ),
        ),
    ),
);

// ... ...
```

对于我这么笨，记忆特别差的人来说，不管是简单的配置，还是复杂的配置，每次当需要使用这些功能时，我都非常害怕且需要从以下三方便获取帮助： 
 + 找曾经写过类似的代码并拷贝过来修改
 + “耐心”（耐着心）查看官方的文档
 + 网上搜索相关的例子
  
因为，每次我都记不住这些配置，但又不得不承认它的实现效果很好。然后我觉得其缺点至少有两点：  
 + 缺点1：尽管是很简单的功能也需要用配置来实现，从而导致配置羞涩难懂
 + 缺点2：配置太复杂，对人的记忆要求太高
  
这是我对Yii框架配置的体会。  

最初，感受到配置式的开发，是在大学的时候做一个OutLook的插件。这个插件需要同步本地和远程服务器的联系人，其中当有冲突时，就有这么几种策略：冲突时以本地为准、冲突时以远程为准、冲突时提醒我、忽略冲突。这有点像我们常用的SVN的处理方式。然而当我在尝试开发实现时，我发现过程很复杂，但处理又是如此相似。这里的区别很微妙，特别这些策略又是由外部用户指定时。最后，我惊讶地发现，如果我使用配置来做的话，会非常简单且明了！因为在这几种策略实现中，有很多重复的功能，如果重复实现势必会导致臃肿的代码。但不同策略又需要体现在不同的实现流程中，最后我采用了配置式的开发方式，并小结得出“优先考虑配置编程，而不实现编程”。 
  
但那时，只是初体会。现在，经过了几年的开发，我才慢慢发现，可以把这种开发模式总结为：开发-配置-使用模式。不知是否有其他模式和此新发现的模式类似？而再结合DI资源服务管理，则可以产出更优质的项目代码。

### 3.1.4 依赖注入的好处

 + 好处1：减少对各个类重复编写单例的开发量  

DI相当于一个容器，里面可以放置基本的变量，也可以放置某类对象实例，甚至是像文件句柄这些的资源。在这容器里面，各个被注册的资源只会存在一份，也就是当被注册的资源为一个实例对象时，其效果就等于单例模式。因此，保存在DI里面的类，不需要再编写获取单例的代码，直接通过DI提供的接口便可巴拉圭单例获取。  

假设很多API的服务组件以及其他的一些业务功能类，都实现了单例获取。分别如：  

微博接口调用：  
```
<?php
class Weibo_Api
{
    protected static $_instance = null;
 
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Weibo_Api();
        }
        return self::$_instance;
    }
}
```

七牛云存储接口调用：
```
<?php
class Qiniu_Api {
    private static $_instance = null; //实例对象
 
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new Qiniu_Api();
        }
        return self::$_instance;
    }
}
```

QQ开放平台接口调用：
```
<?php
class QQ_Api { 
    private static $_instance = null; //实例对象
 
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new QQ_Api();
        }
        return self::$_instance;
    }
}
```

以上明显是重复性的编码，如果使用DI对上面这些服务进行统一管理，则这三个类乃至其他的需要实现单例获取的代码都可以忽略不写。改用DI注册服务的代码如下： 
```
DI()->aStockApi = 'Weibo_Api';
DI()->aDioAopi = 'Qiniu_Api';
DI()->aShopApi = 'QQ_Api';
```
而原来的代码实现，去掉单例模式的代码后简化成：  
```
<?php
class Weibo_Api {	
}
class Qiniu_Api {	
}
class QQ_Api {	
}
```

 + 好处2：统一资源注册，便于后期维护管理

这里引入DI，更多是为了“一处创建，多处使用”这一理念， 而不是各自创建，各自使用。

考虑以下场景：假设需要缓存业务数据，则可事先注册一个实现了缓存机制的实例服务。 
```
DI()->cache = new FileCache();
```
然后提供给多个客户端使用：
```
// 缓存页面内容
DI()->cache->set('indexHtml', $indexContent, 600);   

// 缓存公共配置
DI()->cache->set('config', $config, 86400);  

// 缓存数组数据
DI()->cache->set('artistList', $artistList, 60);   
```
当需要切换文件缓存到高效缓存，如Redis缓存时，只需要重新注册缓存服务即可，如：  
```
DI()->cache = new RedisCache();
```
其他原来的使用保持不变。  

依赖注入的一个很大的优势就在于可以推迟决策，当需要用到某个对象时，才对其实例化。可以让开发人员在一开始时不必要关注过多的细节实现，同时也给后期的扩展和维护带来极大的方便。再上一层，假设未来我们需要更高级的缓存服务，那么我们可以在不影响客户端使用的情况下，轻松升级。例如这里演示的缓存，当有需要时，我们也可以轻易升级切换到多级缓存，还记得前面所学的多级缓存策略吗？  

 + 好处3：延迟式加载，提高性能

延迟加载可以通过DI中的类名初始化、匿名函数这两种方式来实现。  

延迟加载有时候是非常有必要的，如在初始化项目的配置时，随着配置项的数据增加，服务器的性能也将逐渐受到影响，因为配置的内容可能是硬编码，可能来自于数据库，甚至需要通过接口从后台调用获取， 特别当很多配置项不需要使用时。而此时，支持延时加载将可以达到很好的优化，不用担心在需要使用的时候忘记了初始化，同时可以提高服务器性能，提高响应速度。  

如对一些耗时的资源可用匿名函数的进行注册。   
```
DI()->hightResource = function() {
    // 获取返回耗性能的资源
    //return $resource; 
}
```

 + 好处4：以优雅的方式取代滥用的全局变量  

在我个人看来，在实际项目开发中，不应该使用PHP所提供的global关键字，也不应该使用全局变量$_GLOBALS，更不应该到处随意使用。  

而对于全局的变量，应该使用DI来统一管理，即可这样注册： 
```
DI()->debug = true;
```
而不是传统地：  
```
$_GLOBALS['debug'] = true;
```

也许有人会想，这不就是仅仅换了个地方存放变量而已吗？其实不然，而是换一种思想管理和使用资源。以此延伸，DI还可用于改善优化另外两个地方：通过include文件途径对变量的使用和变量的多层传递。 变量的多层传递，通俗来说就是漂洋过海的变量。  

### 3.1.5 DI资源速查表  

#### (1) 速查表

需要注册的DI资源有很多，为了大家统一共识，避免混乱，特将目前已有的DI资源整理如下。  

表3-1 DI资源速查表  

资源名称|是否启动时自动注册|是否必须|接口/类|作用说明
---|---|---|---|---
loader|否|是|[PhalApi_Loader](http://www.phalapi.net/docs/classes/PhalApi_Loader.html)|自动加载：负责PEAR风格下类的自动加载，需要手动注册，指定项目路径
config|否|是|[PhalApi_Config](http://www.phalapi.net/docs/classes/PhalApi_Config.html)|配置：负责项目配置的读取，需要手动注册，指定存储媒介，默认是[PhalApi_Config_File](http://www.phalapi.net/docs/classes/PhalApi_Config_File.html)
logger|否|是|[PhalApi_Logger](http://www.phalapi.net/docs/classes/PhalApi_Logger.html)|日记纪录：负责日记的写入，需要手动注册，指定日记级别和存储媒介，默认是[PhalApi_Logger_File](http://www.phalapi.net/docs/classes/PhalApi_Logger_File.html)
request|是|是|[PhalApi_Request](http://www.phalapi.net/docs/classes/PhalApi_Request.html)|接口参数请求：用于收集接口请求的参数
response|是|是|[PhalApi_Response](http://www.phalapi.net/docs/classes/PhalApi_Response.html)|结果响应：用于输出返回给客户端的结果，默认为[PhalApi_Response_Json](http://www.phalapi.net/docs/classes/PhalApi_Response_Json.html)
notorm|否|推荐|[PhalApi_DB_NotORM](http://www.phalapi.net/docs/classes/PhalApi_DB_NotORM.html)|数据操作：基于NotORM的DB操作，需要手动注册，指定数据库配置
cache|否|推荐|[PhalApi_Cache](http://www.phalapi.net/docs/classes/PhalApi_Cache.html)|缓存：实现缓存读写，需要手动注册，指定缓存
filter|否|推荐|[PhalApi_Filter](http://www.phalapi.net/docs/classes/PhalApi_Filter.html)|拦截器：实现签名验证、权限控制等操作
crypt|否|否|[PhalApi_Crypt](http://www.phalapi.net/docs/classes/PhalApi_Crypt.html)|对称加密：实现对称加密和解密，需要手动注册
curl|否|否|[PhalApi_CUrl](http://www.phalapi.net/docs/classes/PhalApi_CUrl.html)|CURL请求类：通过curl实现的快捷方便的接口请求类，需要手动注册
cookie|否|否|[PhalApi_Cookie](http://www.phalapi.net/docs/classes/PhalApi_Cookie.html)|COOKIE的操作
tracer|是|是|[PhalApi_Helper_Tracer](http://www.phalapi.net/docs/classes/PhalApi_Helper_Tracer.html)|内置的全球追踪器，支持自定义节点标识（1.4.0及上以版本支持）  
debug|否|否|boolean|应用级的调试开关，通常可从配置读取，为true时开启调试模式
_formatterArray|否|否|[PhalApi_Request_Formatter_Array](http://www.phalapi.net/docs/classes/PhalApi_Request_Formatter_Array.html)|数组格式化服务（系统内部使用）
_formatterBoolean|否|否|[PhalApi_Request_Formatter_Boolean](http://www.phalapi.net/docs/classes/PhalApi_Request_Formatter_Boolean.html)|布尔值格式化服务（系统内部使用）
_formatterCallable|否|否|[PhalApi_Request_Formatter_Callable](http://www.phalapi.net/docs/classes/PhalApi_Request_Formatter_Callable.html)|回调格式化服务（系统内部使用）
_formatterDate|否|否|[PhalApi_Request_Formatter_Date](http://www.phalapi.net/docs/classes/PhalApi_Request_Formatter_Date.html)|日期格式化服务（系统内部使用）
_formatterEnum|否|否|[PhalApi_Request_Formatter_Enum](http://www.phalapi.net/docs/classes/PhalApi_Request_Formatter_Enum.html)|枚举格式化服务（系统内部使用）
_formatterFile|否|否|[PhalApi_Request_Formatter_File](http://www.phalapi.net/docs/classes/PhalApi_Request_Formatter_File.html)|上传文件格式化服务（系统内部使用）
_formatterFloat|否|否|[PhalApi_Request_Formatter_Float](http://www.phalapi.net/docs/classes/PhalApi_Request_Formatter_Float.html)|浮点数格式化服务（系统内部使用）
_formatterInt|否|否|[PhalApi_Request_Formatter_Int](http://www.phalapi.net/docs/classes/PhalApi_Request_Formatter_Int.html)|整数格式化服务（系统内部使用）
_formatterString|否|否|[PhalApi_Request_Formatter_String](http://www.phalapi.net/docs/classes/PhalApi_Request_Formatter_String.html)|字符串格式化服务（系统内部使用）


上面以下划线开头的资源名称，表示这些资源会由PhalApi框架自动使用，不需要开发人员手动调用。  

#### (2) DI资源是否已注册的判断误区

当需要判断一个DI资源服务是否已被注册，出于常识会这样判断：  
```
if (isset(DI()->cache)) {
    // 永远无法进入这里
}
```
但这样的判断永远为FALSE，不管注册与否。追其原因在于，DI类使用了魔法方法的方式来提供类成员属性，并存放于```PhalApi_DI::$data```中。这就导致了如果直接使用```isset(DI()->cache)```的话，不会触发魔法方法```PhalApi_DI::__get($name)```的调用，因为确实没有```PhalApi_DI::$cache```这样的成员属性，最终判断都为FALSE。  
  
简单来说，以下两种判断，永远都为FALSE。  
```
var_dump(isset(DI()->XXX));
var_dump(!empty(DI()->XXX));
```

正确判断的写法是：先获取，再判断。例如：  
```
$cache = DI()->cache;
var_dump(isset($cache));
var_dump(!empty($cache));
```  
  
在进行某个资源服务是否存在于DI时，需要注意这一点。   

## 3.2 PEAR包命名规范下的自动加载

首先，PhalApi的自动加载机制很简单；其次，PhalApi不强制只使用一种加载机制。有些框架，单单在类文件的自动加载这一块就弄得非常复杂，以致开发人员需要在使用这些框架的同时添加一些自己的类文件时，往往困难重重，甚至明明用引入了却又不见生效。 而在PhalApi，我们秉承的原则是：简单、统一、规范。  

### 3.2.1 PEAR包命名规范

PEAR包的类文件路径和类名映射非常简单，如下图：
![](images/ch-3-pear-map.png)  
图3-2 来自Autoloading Standard的截图  

出于简单性，PhalApi暂时不使用命名空间，所以namespace这一块可省去。可以看出，这里的映射规则是：把类名中的下划线换成目录分割符，并在最后加上“.php”文件后缀，便可得到类对应的文件路径位置。  

例如，Api_User、Domain_User、Model_User这三个类，分别对应以下路径的文件。  
```
.
|-- Api
|   `-- User.php
|-- Domain
|   `-- User.php
|-- Model
|   `-- User.php
```

再举一个稍微复杂的示例，如类Api_Game_User_Equitment对应的文件路径为：```./Api/Game/User/Equitment.php```。需要注意的是，应该严格区分大小写，因为在Linux、Mac等操作系统，文件路径是区分大小写的。  

下面是一些错误的示例。  

表3-2 错误的类命名  

类名|类文件|错误原因
---|---|---
Api_user|./Api/User.php|类名user小写，导致无法加载
Api_User|./Api/user.php|文件名user小写，导致无法加载
Api_User|./Api_User.php|类文件位置错误，导致无法加载

### 3.2.2 挂靠式自动加载

在准备好类和文件后，怎样才能让这些类被框架自动加载呢？这里提供的方式是：**挂靠式自动加载**。熟悉Linux系统的同学可能很容易明白，还没接触到Linux的同学也是可以很快理解的。这里稍微说明一下。所谓的 挂靠就是将项目内的子目录添加到自动加载器。例如我们在入口文件所看到的，添加商城新项目的项目目录，可以：  
```
DI()->loader->addDirs('Shop');
```
  
当有多个目录时，可以传递一个目录数组。  
```
DI()->loader->addDirs(array('Demo', 'Shop'));
```
需要注意的是，上面相对路径的都需要放置在应用项目的目录API_ROOT下面，暂时不能添加项目以外的目录。  
  
通过```PhalApi_Loader::addDirs($dirs)```方式挂靠的路径，都是强制在目录API_ROOT下面。所传递的目录路径都应该是相对路径。在Linux系统上，下面的三种方式是等效的。  
```
// 路径：API_ROOT/Demo
DI()->loader->addDirs('Demo');

// 路径：API_ROOT/./Demo
DI()->loader->addDirs('./Demo');

// 路径：API_ROOT/Demo
DI()->loader->addDirs('/Demo');
```
如果需要挂靠的目录不在项目目录下，在Linux可以通过软链来解决。  

对于单个文件的引入，可以通过```PhalApi_Loader::loadFile($filePath)```来引入，这里的文件路径可以是相对路径，也可以是绝对路径。注意以下两种写法的区别：  
```
// 路径：API_ROOT/Demo/Tool.php
DI()->loader->loadFile('Demo/Tool.php');

// 路径：/path/to/Demo/Tool.php
DI()->loader->loadFile('/path/to/Demo/Tool.php');
```

在添加代码目录后，便可实现该目录下类文件的自动加载。例如通过```DI()->loader->addDirs('Shop');```添加了Shop项目的源代码目录后，此Shop目录下符合PEAR命名规范的类，都能实现自动加载。
```
$ tree ./Shop/
./Shop/
├── Api
│   ├── Default.php
│   ├── Goods.php
│   └── Welcome.php
├── Common
│   ├── Crypt
│   │   └── Base64.php
│   ├── DB
│   │   └── MSServer.php
│   ├── Logger
│   │   └── DB.php
│   ├── Request
│   │   └── WeiXinFilter.php
│   └── Response
│       └── XML.php
├── Domain
│   └── Goods.php
├── Model
│   └── Goods.php
```  
上面是Shop项目下的部分类文件，当使用类Api_Welcome时，会自动加载./Shop/Api/Welcome.php文件；当使用类Common_Response_XML时，会自动加载./Shop/Common/Response/XML.php文件；当使用类Domain_Goods时，则会自动加载./Shop/Domain/Goods.php文件，以此类推。  

对于面向过程中的函数，而非类，则可以使用```PhalApi_Loader::loadFile($filePath)```来手动引入。  

### 3.2.3 初始化文件和入口文件的区别

使用一个类，其过程可归结为三个步骤。  

 + 1、实现该类
 + 2、自动加载该类
 + 3、在恰当的地方使用该类  

当发现找不到某个类时，应该从这三个步骤分别排查原因。如果尚未实现该类，那么肯定是找不到的，这时可以补充实现。如果已经实现该类但还找不到，则应该检查类名或者类文件路径是否遵循PEAR命名规范。  

例如，有一行这样的代码，却提示类Domain_Goods不存在。  
```
$domain = new Domain_Goods();
```

导致这种情况的可能以下这几种。  

 + 未使用目录分割符而导致错误的类文件路径，如：  
```
// $ vim ./Shop/Domain_Goods.php
<?php
class Domain_Goods{ }
```

 + 因小写而导致错误的类文件路径，如：  
```
// $ vim ./Shop/Domain/goods.php
<?php
class Domain_Goods{ }
```

 + 拼写不完整而导致错误的类名，如：  
```
// $ vim ./Shop/Domain/Goods.php
<?php
class Goods{ }
```

最后如果类名、类文件这些都正确，但仍然还是提示找不到类时，则应该核对第三步，是否在恰当的地方使用该类？恰当的地方是指在添加代码目录之后的调用位置。即在挂靠代码目录前不能使用此目录下的类，而应在挂靠之后使用。用代码示例来表示，则很好理解。例如：  
```
// 错误：未挂靠Shop目录就使用
DI()->response = new Common_Response_XML();

DI()->loader->addDirs('Shop');
```
正确的用法是在挂靠Shop目录后才使用Shop目录里面的类，即：  
```
// 正确：先挂靠Shop目录再使用
DI()->loader->addDirs('Shop');

DI()->response = new Common_Response_XML();
```

到这里，大家有没发现一些有趣的规律，或者一种似曾相识的感觉？上面的示例和背后的原理，大家应该很容易理解，当出现Common_Response_XML类未找到时也能很容易明白错误的原因。但当把这些简单的知识点，隐藏于复杂的上下文场景中时，就会容易导致一些令人感到费解的问题。还记得初始化文件./Public/init.php与项目入口文件./Public/shop/index.php吗？还记得为什么有些资源服务需要在初始化文件中注册，而有些则需要在入口文件中注册？为了唤起记忆，这里稍微回顾一下在这两个文件中分别注册的部分资源。  

初始化文件中的注册：  
```
$loader = new PhalApi_Loader(API_ROOT, 'Library');

// 配置
DI()->config = new PhalApi_Config_File(API_ROOT . '/Config');

// 数据操作 - 基于NotORM
DI()->notorm = new PhalApi_DB_NotORM(DI()->config->get('dbs'), DI()->debug);
```

Shop项目入口文件中的注册：  
```
//装载你的接口
DI()->loader->addDirs('Shop');

// 微信签名验证服务
DI()->filter = 'Common_Request_WeiXinFilter';

// XML返回
DI()->response = 'Common_Response_XML';
```

细心的读者可以发现，在初始化文件中，使用的都是框架已经的类，因为框架本身的类会默认全部能自动被加载。而对于Shop项目中的类，则需要在项目入口文件中使用，这是因为只有手动添加了Shop目录后，该目录下的类文件才能被自动加载。如果在初始化文件中，使用了Shop项目中的类，则会导致类找不到，因为那时尚未加载对应的Shop目录。尤其使用的是类名延迟加载方式时，会把问题隐藏得更深而难以排查。  

这里的经验法则是，先挂靠再使用，在初始化文件中使用框架提供的类，在项目入口文件中使用项目实现的类。如果确实需要在初始化文件中使用项目实现的类，怎么办呢？解决方案可以有多种，例如可以把这些公共的代码放置在扩展类库下，因为扩展类库目录会在初始化文件中默认添加。另一种方案是在初始化文件一开始也把项目目录添加进去，但这样该目录下的全部类都会被自动加载。最后还可以把这些公共的类统一放置在某一个与项目目录无关的目录下，再在初始化文件中进行添加。  

## 3.3 自动生成的在线文档

在线接口文档，主要有两种：  

 + **接口列表文档**
 显示当前项目下全部可用的接口服务，以及各个接口服务的名称、说明信息，如果需要，也可以显示扩展类库下提供的接口服务。  

 + **接口详情文档**
 针对单个接口服务的文档说明，用于显示该接口服务的基本信息、接口说明、接口参数、返回结果以及异常情况等。  
   
下面分别进行解释。  

### 3.3.1 在线接口列表文档

#### (1) 如何访问接口列表文档？

在创建项目后，便可以通过访问Public目录下该项目目录内的```listAllApis.php```文件来访问接口列表文档。例如前面创建的Shop项目，其访问链接为：  
```
http://api.phalapi.net/shop/listAllApis.php
```  
打开后，其文档显示的内容，类似如下：  
![](images/ch-3-list-all-apis.png)  
图3-3 在线接口列表文档的访问效果  

在左边，是全部接口服务类的列表，按接口类名称的字典顺序进行排序。页面右边，是各个接口服务类所提供的全部服务，包括了接口服务、接口名称、更多说明等。  

#### (2) 接口列表文档的注释规范
在上面的访问效果中，有部分的信息提示了“//请使用@desc 注释”或“//请检测接口服务注释”等字样，这是因为接口列表文档的生成，除了依赖于源代码，更大程序上还依赖于规范的注释。  

接口列表的注释比较简单，主要有：  

 + 接口服务类说明

对应接口类文件中文档注释的第一行注释说明。例如，上面提示的“//请检测接口服务注释(Api_Welcome) ”，可以修改对应类文件的注释来调整。  
```
// $ vim ./Shop/Api/Welcome.php
<?php
/**
 * Hello Wolrd示例类
 */
class Api_Welcome extends PhalApi_Api {
```

 + 序号

自动生成，不需要注释。把接口服务名称```Class.Action```按字典排序后从小到大依次编号。 

 + 接口服务

自动生成，通过反射自动获取接口类的全部可访问的方法，并提取可用的接口服务，即对应service参数的接口服务名称。  

 + 接口名称

接口中文名称，不带任何注解的注释，通常为接口类函数成员的第一行注释。如上面提示的“//请使用@desc 注释”，可以通过类函数成员的注释来调整。  
```
    /**
     * 欢迎光临
     */
    public function say() {
```

 + 更多说明

对应接口类函数成员的```@desc```注释。如上面提示的“//请使用@desc 注释”，可以添加```@desc```注释来调整。  
```
    /**
     * 欢迎光临
     * @desc 简单的Hello Wolrd返回
     */
    public function say() {
```

按上面的注释规范调整好后，刷新刚才的接口列表页面，可以看到在线文档已经实时同步更新。  
![](images/ch-3-list-all-apis-welcome-say.png)  
图3-4 调整后的在线接口文档  

#### (3) 显示扩展类库的接口服务

查看```listAllApis.php```文件的源代码，可以看到默认情况下，已添加了若干个扩展类库的接口服务显示，包括User扩展、Auth扩展、七牛扩展。

```
//$ vim ./Public/shop/listAllApis.php
$libraryPaths = array(
    'Library/User/User',    // User扩展
    'Library/Auth/Auth',    // Auth扩展
    'Library/Qiniu/CDN',    // 七牛扩展
);

// 初始化
require_once implode(D_S, array($root, '..', 'init.php'));

// 处理项目
DI()->loader->addDirs($apiDirName);
```
如果需要显示其他扩展类库下的接口服务，则需要将对应的项目目录参考上面的写法添加进来。添加好后，记得要在```checkApiParams.php```文件中同步添加。 

顺便提一下，框架的一致性，在这里也得到了体现。那就是任何类的使用，都应先挂靠再使用。在添加了扩展类库的目录后，在线接口列表文档才能显示对应扩展类库下的接口服务。  

### 3.3.2 在线接口详情文档

### (1) 如何访问在线接口详情文档？

在进入上面的在线接口列表文档后，点击对应的接口服务即可跳转到对应的接口详情文档。或者，也可以手动拼接访问。它的访问路径与```listAllApis.php```类似，但需要访问的是```checkApiParams.php```文件，并且需要使用service参数指定需要查看的接口服务。例如查看我们之前定义的获取商品快照信息的接口服务。  

在浏览器访问打开：   
```
http://api.phalapi.net/shop/checkApiParams.php?service=Goods.Snapshot
```
打开后，访问效果类似如下：  
![](images/ch-3-check-goods-snapshot.png)
图3-5 商品快照信息的在线接口详情文档  

### (2) 在线接口详情文档的注释规范

接口文档的注释较多，开发人员可在进行项目开发时按需注释。但各部分的规范也是简单明了的。结合上面的访问效果和商品快照信息接口类这一示例，从上到下，依次讲解各部分的使用规范。    

 + 接口

当前接口服务的service名称，即：```Class.Action```。  

 + 接口名称

接口中文名称，不带任何注解的注释，通常为接口类函数成员的第一行注释。如：     
```
    /**
     * 获取商品快照信息
     */
    public function snapshot() {
```

 + 接口说明

对应接口类函数成员的```@desc```注释。如：
```
    /**
     * @desc 获取商品基本和常用的信息
     */
    public function snapshot() {
```

 + 接口参数

根据接口类配置的参数规则自动生成，即对应当前接口类```getRules()```方法中的返回。其中最后的“说明” 字段对应参数规则中的desc选项。可以配置多个参数规则。此外，配置文件./Config/app.php中的公共参数规则也会显示在此接口参数里。这里的参数规则是：     
```
    public function getRules() {
        return array(
            'snapshot' => array(
                'id' => array('name' => 'id', 'require' => true, 'type' => 'int', 'min' => 1, 'desc' => '商品ID'),
            ),
        );
    }
```

 + 返回结果

对应接口类函数成员的```@return```注释，可以有多组，格式为：```@return 返回类型 返回字段 说明```。这里是：  
```
    /**
     * @return int      goods_id    商品ID
     * @return string   goods_name  商品名称 
     * @return int      goods_price 商品价格
     * @return string   goods_image 商品图片
     */
    public function snapshot() {
```

 + 异常情况

对应```@exception```注释，可以有多组，格式为：```@exception 错误码 错误描述信息```。例如，我们可以在此示例中补充异常情况。  
```
    /**
     * @exception 406 签名失败
     */
    public function snapshot() {
```
刷新后，可以看到新增的异常情况说明。  
![](images/ch-2-goods-snapshot-docs-exception.png)
图3-6 添加了异常情况后的效果  

以上获取商品快照信息接口服务的参数规则和注释，完整的代码为：  
```
// $ vim ./Shop/Api/Goods.php 
<?php
class Api_Goods extends PhalApi_Api {

    public function getRules() {
        return array(
            'snapshot' => array(
                'id' => array('name' => 'id', 'require' => true, 'type' => 'int', 'min' => 1, 'desc' => '商品ID'),
            ),
        );
    }

    /**
     * 获取商品快照信息
     * @desc 获取商品基本和常用的信息
     * @return int      goods_id    商品ID
     * @return string   goods_name  商品名称 
     * @return int      goods_price 商品价格
     * @return string   goods_image 商品图片
     * @exception 406 签名失败
     */
    public function snapshot() {
        ... ...
    }
}
```

## 3.5 接口查询语言与SDK包


### 3.5.1 用一句话来描述接口请求

为了统一规范客户端请求调用接口服务的使用，在尽量保证简单易懂的前提下也兼顾使用的流畅性，为此我们专门设计了内部领域特定语言：**接口查询语言**（Api Structured Query Language）。通过接口查询语言，最后可以用一句话来描述接口请求。  
   
从外部DSL的角度来看此接口查询的操作，可总结为创建、初始化、重置、参数设置和请求等操作。  
```
create

withHost host
withFilter filter
withParser parser

reset   #特别注意：重复查询时须重置请求状态

withService service
withParams paramName1 paramValue1
withParams paramName2 paramValue2
withParams ... ...
withTimeout timeout

request
```
  
根据此设计理念，任何语言都可实现此接口查询的具体操作。  

### 3.5.2 接口查询语言设计理念与使用示例

接口查询语言的文法是：```create -> with -> request```。所用到的查询文法解释如下。虽然顺序不强制，但通常是从上往下依次操作。  

表3-3 接口查询的文法   

操作|参数|是否必须|是否可重复调用|作用说明
---|---|---|---|---
create|无|必须|可以，重复调用时新建一个实例，非单例模式|需要先调用此操作创建一个接口实例
withHost|接口域名|必须|可以，重复时会覆盖|设置接口域名，如：http://api.phalapi.net/
withFilter|过滤器|可选|可以，重复时会覆盖|设置过滤器，与服务器的DI()->filter对应，需要实现PhalApiClientFilter接口
withParser|解析器|可选|可以，重复时会覆盖|设置结果解析器，仅当不是JSON返回格式时才需要设置，需要实现PhalApiClientParser接口
reset|无|通常必须|可以|重复查询时须重置请求状态，包括接口服务名称、接口参数和超时时间
withService|接口服务名称|通常必选|可以，重复时会覆盖|设置将在调用的接口服务名称，如：Default.Index
withParams|接口参数名、值|可选|可以，累加参数|设置接口参数，此方法是唯一一个可以多次调用并累加参数的操作
withTimeout|超时时间|可选|可以，重复时会覆盖|设置超时时间，单位毫秒，默认3秒
request|无|必选|可以，重复发起接口请求|最后执行此操作，发起接口请求

以JAVA客户端为例，先来演示如何调用SDK包调用接口服务。  

最简单的调用，也就是默认接口的调用。只需要设置接口系统域名及入口路径，不需要指定接口服务，也不需要添加其他参数。      
```
PhalApiClientResponse response = PhalApiClient.create()
       .withHost("http://demo.phalapi.net/")   //接口域名
       .request();                             //发起请求
```
  
通常的调用，需要设置接口服务名称，添加接口参数，并指定超时时间。  
```
PhalApiClientResponse response = PhalApiClient.create()
       .withHost("http://demo.phalapi.net/")
       .withService("Default.Index")          //接口服务
       .withParams("username", "dogstar")     //接口参数
       .withTimeout(3000)                     //接口超时
       .request();
```
  
更高级、更复杂的调用，可根据需要再设置过滤器、解析器，以完成定制化扩展的功能。    
```
PhalApiClientResponse response = PhalApiClient.create()
       .withHost("http://demo.phalapi.net/")
       .withService("Default.Index")
       .withParser(new PhalApiClientParserJson()) //设置JSON解析，默认已经是此解析，这里仅作演示
       .withParams("username", "dogstar")
       .withTimeout(3000)
       .request();
```

当接口请求超时时，统一返回```ret = 408```表示接口请求超时。此时可进行接口重试。  
  
需要重试时，可先判断返回的状态码再重新请求。    
```
PhalApiClient client = PhalApiClient.create()
     .withHost("http://demo.phalapi.net/")

PhalApiClientResponse response = client.request();

if (response.getRet() == 408) {
     response = client.request(); //请求重试
}
```

### 3.5.3 更好的建议

#### (1) 不支持面向对象的实现方式
上面介绍的接口查询的用法是属于基础的用法，其实现与宿主语言有强依赖关系，在不支持面向对象语言中，可以使用函数序列的方式，例如下面面向过程的伪代码示例。    
```
create();
withHost('http://demo.phalapi.net/');
withService('Default.Index');
withParams('username', 'dogstar');
withTimeout(3000);
rs = request();
```

### (2) 封装自己的接口实例
通常，在一个项目里面我们只需要一个接口实例即可，但此语言没默认使用单例模式，是为了大家更好的自由度。基于此，大家在项目开发时，可以再进行封装：提供一个全局的接口查询单例，并组装基本的接口公共查询属性。即分两步：初始化接口实例，以及接口具体的查询操作。  
  
如第一步先初始化：
```
PhalApiClient client = PhalApiClient.create()
     .withHost("http://demo.phalapi.net/")
     .withParser(new PhalApiClientParserJson());
```
  
第二步进行具体的接口请求：  
```
PhalApiClientResponse response = client.reset()  //重复查询时须重置
     .withService("Default.Index")
     .withParams("username", "dogstar")
     .withTimeout(3000)
     .request();
```
  
这样，在其他业务场景下就不需要再重复设置这些共同的属性（如过滤器、解析器）或者共同的接口参数。

### 3.5.4 Java版SDK包的使用说明

虽然上面简单演示了JAVA版SDK包的使用，但为了给实际项目开发提供更详细的参考，这里再补充一下更具体的使用说明。首先，需要将框架目录下的./SDK/JAVA/net目录中的全部代码拷贝到项目，然后便可以开始使用了。  

#### (1) 使用说明

首先，我们需要导入SDK包：
```
import net.phalapi.sdk.*;
```

然后，准备一个子线程调用，并在此线程中实现接口请求：
```
    /**
     * 网络操作相关的子线程
     */  
    Runnable networkTask = new Runnable() {  
      
        @Override  
        public void run() {  
            // TODO 在这里进行 http request.网络请求相关操作  
            
        	PhalApiClient client = PhalApiClient.create()
	       			    .withHost("http://demo.phalapi.net/");
	       	
	       	PhalApiClientResponse response = client
	       			    .withService("Default.Index")
	       			    .withParams("username", "dogstar")
	       			    .withTimeout(3000)
	       			    .request();

	   		String content = "";
	   		content += "ret=" + response.getRet() + "\n";
	   		if (response.getRet() == 200) {
				try {
					JSONObject data = new JSONObject(response.getData());
					content += "data.title=" + data.getString("title") + "\n";
					content += "data.content=" + data.getString("content") + "\n";
					content += "data.version=" + data.getString("version") + "\n";
				} catch (JSONException ex) {
					  
				}
	   		}
			content += "msg=" + response.getMsg() + "\n";
			
			Log.v("[PhalApiClientResponse]", content);
            
            Message msg = new Message();  
            Bundle data = new Bundle();  
            data.putString("value", content);  
            msg.setData(data);  
            handler.sendMessage(msg); 
        }  
    }; 
```
  
接着，实现线程回调的hander：
```
    Handler handler = new Handler() {  
        @Override  
        public void handleMessage(Message msg) {  
            super.handleMessage(msg);  
            Bundle data = msg.getData();  
            String val = data.getString("value");  
            Log.i("mylog", "请求结果为-->" + val);  
            // TODO  
            // UI界面的更新等相关操作  
        }  
    }; 
``` 
  
最后，在我们需要的地方启动：
```
    View.OnClickListener mDummyBtnClickListener = new View.OnClickListener() {
        
        @Override
        public void onClick(View arg0) {
            // 开启一个子线程，进行网络操作，等待有返回结果，使用handler通知UI  
            new Thread(networkTask).start();  
            
            // ....
        }
    };
```

当我们需要再次使用同一个接口实例进行请求时，需要先进行重置，以便清空之前的接口参数，如：
```
//再一次请求
response = client.reset() //重置
		.withService("User.GetBaseInfo")
		.withParams("user_id", "1")
		.request();


content = "";
content += "ret=" + response.getRet() + "\n";
if (response.getRet() == 200) {
	try {
		JSONObject data = new JSONObject(response.getData());
		JSONObject info = new JSONObject(data.getString("info"));
		
		content += "data.info.id=" + info.getString("id") + "\n";
		content += "data.info.name=" + info.getString("name") + "\n";
		content += "data.info.from=" + info.getString("from") + "\n";
	} catch (JSONException ex) {
		  
	}
}
content += "msg=" + response.getMsg() + "\n";

Log.v("[PhalApiClientResponse]", content);
```
  
异常情况下，即ret != 200时，将返回错误的信息，如：
```
//再来试一下异常的请求
response = client.reset()
		.withService("Class.Action")
		.withParams("user_id", "1")
		.request();

content = "";
content += "ret=" + response.getRet() + "\n";
content += "msg=" + response.getMsg() + "\n";

Log.v("[PhalApiClientResponse]", content);
```

运行后，查询log，可以看到：
![](images/ch-3-java-sdk.jpg)
图3-7 JAVA版SDK包运行后的效果截图  

可以注意到，在调试模式时，会有接口请求的链接和返回的结果日记。   
```
10-17 07:40:55.268: D/[PhalApiClient requestUrl](1376): http://demo.phalapi.net/?service=User.GetBaseInfo&user_id=1
10-17 07:40:55.364: D/[PhalApiClient apiResult](1376): {"ret":200,"data":{"code":0,"msg":"","info":{"id":"1","name":"dogstar","from":"oschina"}},"msg":""}
```

#### (2) 扩展你的过滤器和结果解析器

 + 扩展过滤器

当服务端接口需要接口签名验证，或者接口参数加密传送，或者压缩传送时，可以实现此过滤器，以便和服务端操持一致。  
  
当需要扩展时，分两步。首先，需要实现过滤器接口：  
```
class MyFilter implements PhalApiClientFilter {

        public void filter(String service, Map<String, String> params) {
            // TODO ...
        }
}
```
然后设置过滤器：
```
PhalApiClientResponse response = PhalApiClient.create()
		   .withHost("http://demo.phalapi.net/")
		   .withFilter(new MyFilter())
		   // ...
		   .request();
```

 + 扩展结果解析器

当返回的接口结果不是JSON格式时，可以重新实现此接口。  
  
当需要扩展时，同样分两步。类似过滤器扩展，这里不再赘述。 

### 3.5.5 Ruby版SDK包的使用说明

遵循前面制定的接口查询语言，不同语言的SDK的使用是类似的。为了说明这一点，并且强调接口查询语言的文法，这里再以Ruby版本的SDK为例，进一步简单说明。  

当需要使用Ruby版的SDK包时，先将框架目录下的./SDK/Ruby/PhalApiClient目录中的全部代码拷贝到项目。 

#### (1) 使用说明

首先，我们需要导入SDK包：
```
# demo.rb
require_relative './PhalApiClient/phalapi_client'
```

然后，创建客户端实例，发起接口请求。  
```
a_client = PhalApi::Client.create.withHost('http://demo.phalapi.net')
a_response = a_client.withService('Default.Index').withParams('username', 'dogstar').withTimeout(3000).request()

puts a_response.ret, a_response.data, a_response.msg
```
 
运行后，可以看到：  
```
200
{"title"=>"Hello World!", "content"=>"dogstar您好，欢迎使用PhalApi！", "version"=>"1.2.1", "time"=>1445741092}
```

当需要重复调用时，需要先进行重置操作reset，如：
```
# 再调用其他接口
a_response = a_client.reset \
    .withService("User.GetBaseInfo") \
    .withParams("user_id", "1") \
    .request

puts a_response.ret, a_response.data, a_response.msg
```
  
当请求有异常时，返回的ret!= 200，如：
```
# 非法请求
a_response = a_client.reset.withService('XXXX.noThisMethod').request

puts a_response.ret, a_response.data, a_response.msg
```
  
以上的输出为： 
```
400
非法请求：接口服务XXXX.noThisMethod不存在
```

#### (2) 扩展你的过滤器和结果解析器

 + 扩展过滤器  

当服务端接口需要接口签名验证，或者接口参数加密传送，或者压缩传送时，可以实现此过滤器，以便和服务端操持一致。  
 
当需要扩展时，分两步。首先，需要实现过滤器接口：  
```
class MyFilter < PhalApi::ClientFilter 
        def filter(service, *params)
            #TODO ...
        end
}
```

然后设置过滤器：
```
a_response = PhalApi::Client.create.withHost('http://demo.phalapi.net') \
	   .withFilter(MyFilter.new) \
	   # ... \
	   .request
```

 + 扩展结果解析器  

当返回的接口结果不是JSON格式时，可以重新实现此接口。  
 
当需要扩展时，同样分两步。类似过滤器扩展，这里不再赘述。

除了Java和Ruby外，目前已提供的SDK包还有C#版、Golang版、Object-C版、Javascript版、PHP版、Python版等。其他语言的SDK包使用类似，这里不再赘述。  

## 3.6 脚本命令的使用

自动化是提升开发效率的一个有效途径。PhalApi致力于简单的接口服务开发，同时也致力于通过自动化提升项目的开发速度。为此，提供了创建项目、生成单元测试骨架代码、生成数据库建表SQL、生成接口文件代码这些脚本命令。应用这些脚本命令，能快速完成重复但消耗时间的工作。下面将分别进行说明。  

在使用这些脚本命令前，需要注意以下几点。  

第一点是执行权限，当未设置执行权限时，脚本命令会提示无执行权限，类似这样。  
```
$ ./PhalApi/phalapi-buildapp 
-bash: ./PhalApi/phalapi-buildapp: Permission denied
```
那么需要这样设置脚本命令的执行权限。  
```
$ chmod +x ./PhalApi/phalapi-build*
```
  
其次，对于Linux平台，可能会存在编码问题，例如提示：  
```
$ ./PhalApi/phalapi-buildapp 
bash: ./PhalApi/phalapi-buildapp: /bin/bash^M: bad interpreter: No such file or directory
```
这时，可使用dos2unix命令转换一下编码。  
```
$ dos2unix ./PhalApi/phalapi-build*
dos2unix: converting file ./PhalApi/phalapi-buildapp to Unix format ...
dos2unix: converting file ./PhalApi/phalapi-buildcode to Unix format ...
dos2unix: converting file ./PhalApi/phalapi-buildsqls to Unix format ...
dos2unix: converting file ./PhalApi/phalapi-buildtest to Unix format ...
```

最后一点是，在任意目录位置都是可以使用这些命令的，但会与所在的项目目录绑定。通常，为了更方便使用这些命令，可以将这些命令软链到系统命令下。例如：  
```
$ cd /pah/to/PhalApi/PhalApi
$ sudo ln -s /path/to/phalapi-buildapp /usr/bin/phalapi-buildapp
$ sudo ln -s /path/to/phalapi-buildsqls /usr/bin/phalapi-buildsqls
$ sudo ln -s /path/to/phalapi-buildtest /usr/bin/phalapi-buildtest
$ sudo ln -s /path/to/phalapi-buildcode /usr/bin/phalapi-buildcode
```

### 3.6.1 phalapi-buildapp命令

 ```phalapi-buildapp```脚本命令，可用于创建一个新的项目，最终效果和在线安装向导类似。其使用说明如下：  
![](images/ch-3-buildapp.png)  
图3-8 phalapi-buildapp命令的Usage  
  
其中，

 + 第一个参数app：是待创建的项目名称，通常以字母开头，由字母和数字组成  

例如，现在让我们来创建一个新的项目，假设是用来提供活动相关接口服务的，名称为：act，那么可以执行以下命令。  
```
$ ./PhalApi/phalapi-buildapp act
```

执行后，会看到类似以下的输出。  
```
create Act ...
create Act tests ...
create Act bootstarp ...

OK! Act has been created successfully!
```

最后，可以看到会增加了以下两个目录，一个是放置act项目源代码和单元测试的目录。  
```
$ tree ./Act/
./Act/
├── Api
│   └── Default.php
├── Common
├── Domain
├── Model
└── Tests
    ├── Api
    │   └── Api_Default_Test.php
    ├── Common
    ├── Domain
    ├── Model
    ├── phpunit.xml
    └── test_env.php

9 directories, 4 files
```

另一个是该项目对外访问的目录，包括入口文件、在线文档访问文件。
```
$ tree ./Public/act/
./Public/act/
├── checkApiParams.php
├── index.php
└── listAllApis.php

0 directories, 3 files
```

我们还可以试请求一下默认接口服务，发现也是可以正常响应的。  
```
$ curl "http://api.phalapi.net/act/"
{"ret":200,"data":{"title":"Hello World!","content":"PHPer\u60a8\u597d\uff0c\u6b22\u8fce\u4f7f\u7528PhalApi\uff01","version":"1.4.0","time":1494343386},"msg":""}
```

最后需要注意的是，在创建新项目时，是以Demo项目为模板进行创建的。所以在使用phalapi-buildapp命令创建新项目时，应确保默认的Demo项目目录和文件未被删除，否则会导致创建异常。默认的Demo项目目录包括放置源代码的目录./Demo和对外可访问的目录./Public/demo。  

另外，当重复创建相同的项目时，会提示项目已存在。如再次创建act项目。  
```
$ ./PhalApi/phalapi-buildapp act
Error: Act exists!
```

### 3.6.2 phalapi-buildtest命令

当需要对某个类进行单元测试时，可使用```phalapi-buildtest```脚本生成对应的单元测试骨架代码，其使用说明如下：  
![](images/ch-3-buildtest.png)  
  
其中，

 + 第一个参数file_path：是待测试的源文件相对/绝对路径  
 + 第二个参数class_name：是待测试的类名  
 + 第三个参数bootstrap：是测试启动文件，通常是/path/to/test_env.php文件  
 + 第四个参数author：你的名字，默认是dogstar  
   
通常，可以先写好类名以及相应的接口，然后再使用此脚本生成单元测试骨架代码。以Shop项目中Hello World接口为例为例，当需要为Api_Welcome类生成单元测试骨架代码时，可以依次这样操作。  
```
$ cd ./Shop/Tests
$ ../../PhalApi/phalapi-buildtest ../Api/Welcome.php Api_Welcome ./test_env.php > ./Api/Api_Welcome_Test.php
```
  
最后，需要将生成好的骨架代码，重定向保存到你要保存的位置。通常与产品代码对齐，并以“{类名} + _Test.php”方式命名，如这里的Api_Welcome_Test.php。  

生成的骨架代码类似如下，为节省边幅，注释已省略。  
```
// Tests$ vim ./Api/Api_Welcome_Test.php
<?php
//require_once dirname(__FILE__) . '/test_env.php';

if (!class_exists('Api_Welcome')) {
    require dirname(__FILE__) . '/../Api/Welcome.php';
}

class PhpUnderControl_ApiWelcome_Test extends PHPUnit_Framework_TestCase
{
    public $apiWelcome;

    protected function setUp()
    {
        parent::setUp();

        $this->apiWelcome = new Api_Welcome();
    }

    protected function tearDown()
    {
    }

    public function testGetRules()
    {
        $rs = $this->apiWelcome->getRules();
    }

    public function testSay()
    {
        $rs = $this->apiWelcome->say();
    }
}
```
这里，还需要根据情况手动更改一下test_env.php测试环境文件的位置，即去掉注释并改成：  
```
require_once dirname(__FILE__) . '/../test_env.php';
```

此时生成的单元测试骨架，会对public访问级别的函数成员生成一一对应的测试用例，并具备一些基本的验证功能。对于刚生成的单元测试，可以试运行一下。  
```
Tests$ phpunit ./Api/Api_Welcome_Test.php 
PHPUnit 4.3.4 by Sebastian Bergmann.

..

Time: 7 ms, Memory: 6.50Mb

OK (2 tests, 0 assertions)
```

phalapi-buildtest命令还有一些很有趣的功能。单元测试可按照构建-执行-验证的模式来编写，所以使用phalapi-buildtest生成的骨架代码，除了会生成执行环节的代码外，还可以生成构建和验证的代码。让我们来看一些具体的示例。  

继续来看一下获取商品快照信息接口服务的领域层的实现，可以看到之前的代码是这样的。  
```
// $ vim ./Shop/Domain/Goods.php 
<?php
class Domain_Goods {

    public function snapshot($goodsId) {
        $model = new Model_Goods();
        $info = $model->getSnapshot($goodsId);

        if (empty($info) || $info['goods_price'] <= 0) {
            return array();
        }

        return $info;
    }
}
```
暂且先不关注这里具体的实现。这里需要一个没有缺省值的```$goodsId```参数，并且返回的是一个数组。phalapi-buildtest命令会自动识别参数列表，以及使用参数缺省值填充，但对于返回值的类型验证，则需要依据函数成员的```@return```注解。为此，我们可以先添加返回类型为数组的注解。  
```
    /**
     * @return array 快照信息
     */
    public function snapshot($goodsId) {
```
随后，根据phalapi-buildtest命令的使用说明，为Domain_Goods生成单元测试骨架代码，并保存到对应的测试目录。这一次，让我们先进入Shop项目的Tests目录，再使用命令，因为通常情况下使用单元测试时我们都是在此目录下的。可以看到，所在目录位置对phalapi-buildtest命令的使用是不影响的。  
```
cd Shop/Tests/
Tests$ ../../PhalApi/phalapi-buildtest ../Domain/Goods.php Domain_Goods
```
籍此机会，顺便再来分解下phalapi-buildtest命令的使用过程。对于待测试的类是独立的类时，即不继承于其他类，则可以忽略第三个参数bootstrap，因为这里不需要用到框架的自动加载。同时，在保存生成的骨架代码前，可以先预览一下所生成的代码是否正确。如执行完上面这行命令后，可以看到类似这样的输出。  
```
<?php
/**
 * PhpUnderControl_DomainGoods_Test
 *
 * 针对 ../Domain/Goods.php Domain_Goods 类的PHPUnit单元测试
 *
 * @author: dogstar 20170510
 */

//require_once dirname(__FILE__) . '/test_env.php';

if (!class_exists('Domain_Goods')) {
    require dirname(__FILE__) . '/../Domain/Goods.php';
}

class PhpUnderControl_DomainGoods_Test extends PHPUnit_Framework_TestCase
{
    ... ...
```

预览确认生成的骨架代码没问题后，再重定向保存到测试文件。  
```
Tests$ ../../PhalApi/phalapi-buildtest ../Domain/Goods.php Domain_Goods > ./Domain/Domain_Goods_Test.php
```
测试文件名为待测试的类名，加上“_Test.php”后缀。保存后，记得再适当调整一下test_env.php文件的引入路径。  
```
// Tests$ vim ./Domain/Domain_Goods_Test.php
require_once dirname(__FILE__) . '/../test_env.php';
```

然后，执行一下此测试文件，可以看到是可以正常执行并通过测试的。之所以通常，是因为在找不到对应的商品信息时，默认返回空数组。  
```
Tests$ phpunit ./Domain/Domain_Goods_Test.php 
PHPUnit 4.3.4 by Sebastian Bergmann.

.

Time: 35 ms, Memory: 6.50Mb

OK (1 test, 1 assertion)
```

再回头看一下这里生成的骨架代码，看下最终生成了哪些构建的代码，又生成了发些验证的代码。  
```
// Tests$ vim ./Domain/Domain_Goods_Test.php
    public function testSnapshot()
    {
        $goodsId = '';

        $rs = $this->domainGoods->snapshot($goodsId);

        $this->assertTrue(is_array($rs));
    }
```
由于```$goodsId```参数没有缺省值，所以这里给了空字符串，一来不管参数是数值还是字符串都方便填充测试数据，二来不会导致生成的代码语法上出错。在最后，还进行了简单的断言，对```Domain_Goods::snapshot($goodsId)```方法返回值的类型进行了检测，判断是否为期望的数组类型。是不是觉得很有趣？你也可以亲自动手，试下参数带有缺省值的情况。   

phalapi-buildtest命令除了能根据参数列表生成构建代码，根据```@return```注解生成类型断言代码外，还可以根据```@testcase```注解生成对应的测试用例代码。```@testcase```注解的格式是：```@testcase 期望返回结果 参数1,参数2,参数3 ...```，第一个是期望返回的结果，后面是提供给待测试函数的参数列表，用英文逗号分割。目前此方式适合用于参数和返回值是基本类型的场景。由于上面商品快照返回的类型是数组，非基本类型，为了演示```@testcase```注解的效果，让我们来看另一个示例。  

假如我们现在有一个实现了加法运算的简单计算器类，并通过```@testcase```注解添加了两组测试用例，分别是```2 = 1 + 1```和```-5 = -10 + 5```。实现代码和注释如下。  
```
<?php
class Calculator {
    /**
     * 求两数和
     *
     * @testcase 2 1,1
     * @testcase -5 -10,5
     * @return int
     */
    public function add($left, $right) {
        return $left + $right;
    }
}
```
使用phalapi-buildtest命令生成骨架代码后，可以发现除了下面默认的测试用例外，还根据```@testcase```注解生成了两个测试用例。  
```
    public function testAdd()
    {
        $left = '';
        $right = '';

        $rs = $this->calculator->add($left, $right);

        $this->assertTrue(is_int($rs));
    }
```

根据```@testcase 2 1,1```注释生成的测试用例是：  
```
    public function testAddCase0()
    {
        $rs = $this->calculator->add(1,1);

        $this->assertEquals(2, $rs);
    }
```
根据```@testcase -5 -10,5```注释生成的测试用例是： 
```
    public function testAddCase1()
    {
        $rs = $this->calculator->add(-10,5);

        $this->assertEquals(-5, $rs);
    }
```

关于单元测试的维护，以及如何针对不同的场景编写单元测试，如何采用测试驱动进行开发，将会在后面深入讲解。  

###(3)生成数据库建表SQL
当需要创建数据库表时，可以使用```phalapi-buildsqls```脚本结合配置文件dbs.php生成建表SQL，这个工具在创建分表时尤其有用，其使用如下：  
![](http://7xiz2f.com1.z0.glb.clouddn.com/2_20160422210230.jpg)  
  
其中，

 + 第一个参数dbs_config：是指向数据库配置文件./Config/dbs.php的路径，可以使用相对路径  
 + 第二个参数table：是需要创建sql的表名，每次生成只支持一个  
 + 第三个参数engine：（可选）是指数据库表的引擎，可以是：Innodb或者MyISAM  
  
> 温馨提示：需要提前先将建表的SQL语句（除主键id和ext_data字段外）放置到./Data/目录下，文件名为：{表名}.sql。  
  
如，我们需要生成10用户user_session表的的建表语句，那么需要添加数据文件./Data/user_session.sql（除主键id和ext_data字段外）：  
```
      `user_id` bigint(20) DEFAULT '0' COMMENT '用户id',
      `token` varchar(64) DEFAULT '' COMMENT '登录token',
      `client` varchar(32) DEFAULT '' COMMENT '客户端来源',
      `times` int(6) DEFAULT '0' COMMENT '登录次数',
      `login_time` int(11) DEFAULT '0' COMMENT '登录时间',
      `expires_time` int(11) DEFAULT '0' COMMENT '过期时间',
```
  
然后，进入到项目根目录，执行命令：  
```
$ php ./PhalApi/phalapi-buildsqls ./Config/dbs.php user_session
```
  
就会看到生成好的SQL语句了，类似：  
```
CREATE TABLE `phalapi_user_session_0` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      ... ...
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_1` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      ... ...
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_2` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      ... ...
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_3` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      ... ...
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_4` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      ... ...
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_5` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      ... ...
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_6` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      ... ...
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_7` (
      ... ...
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_8` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      ... ...
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phalapi_user_session_9` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      ... ...
      `ext_data` text COMMENT 'json data here',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```
  
最后，便可把生成好的SQL语句，导入到数据库，进行建表操作。

###(4)生成接口代码 - V1.3.4及以上版本支持
当需要编写开发一个接口时，可以使用```phalapi-buildcode```脚本生成基本的Api、Domain和Model代码。此脚本不是很强悍的，项目可以根据自己的喜欢使用，或者修改定制自己的模板。其使用如下：  
![](http://7xiz2f.com1.z0.glb.clouddn.com/4_20160514155143.png)  
  
其中，

 + 第一个参数app_path：是指项目根目录到你的项目的相对路径  
 + 第二个参数api_path：是需要创建接口的相对项目的相对路径，支持多级目录，可不带.php后缀  
 + 第三个参数author：（可选）你的名字，默认为空
 + 第四个参数overwrite：（可选）是否覆盖已有的代码文件，默认为否
  
例如，我们要为Demo项目生成一个新的接口文件./AA/BB/CC.php，则可以：  
```
$ cd /path/to/PhalApi
$ ./PhalApi/phalapi-buildcode Demo AA/BB/CC dogstar
Start to create folder /mnt/hgfs/F/PHP/PhalApi/PhalApi/../Demo/Api/AA/BB ...
Start to create folder /mnt/hgfs/F/PHP/PhalApi/PhalApi/../Demo/Domain/AA/BB ...
Start to create folder /mnt/hgfs/F/PHP/PhalApi/PhalApi/../Demo/Model/AA/BB ...
Start to create file /mnt/hgfs/F/PHP/PhalApi/PhalApi/../Demo/Api/AA/BB/CC.php ...
Start to create file /mnt/hgfs/F/PHP/PhalApi/PhalApi/../Demo/Domain/AA/BB/CC.php ...
Start to create file /mnt/hgfs/F/PHP/PhalApi/PhalApi/../Demo/Model/AA/BB/CC.php ...

OK! AA/BB/CC has been created successfully!

```
  
可以看到生成的代码有：  
![](http://7xiz2f.com1.z0.glb.clouddn.com/cc20160514155950.png)   
    
访问接口：  
![](http://7xiz2f.com1.z0.glb.clouddn.com/aa20160514160328.png)  
    
最后，在线接口列表，可以看到：  
![](http://7xiz2f.com1.z0.glb.clouddn.com/bb20160514160158.png)  

## 3.7 构建更强大的接口服务
## 3.8 可重用的扩展类库
## 本章小结
## 参考资料