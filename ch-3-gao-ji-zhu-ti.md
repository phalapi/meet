# 第3章 高级主题

__设计软件有两种方法：一种是简单到明显没有缺陷，另外一种复杂到缺陷不那么明显。——托尼.霍尔__   

在学习了前面第2章的基础入门后，已经可以能够胜任一般性的接口服务功能开发，和应付根据项目具体情况而定制扩展的业务场景。但在更高层面，对于为什么需要这样实现，以及各种开发实现背后所蕴含的理念、规范和原理，则需要通过这一章的学习来获得。高级主题介绍的虽然不是可以直接应用于具体开发的技艺，但却是能够更好指导我们进行恰如其分接口开发的抽象思想。例如对各种后端资源服务的管理，类文件的命名规范和自动加载，以及架构明显的编程风格。  

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

### 3.1.2 架构明显的编程风格

#### (1) 依赖注入的基本使用

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
  
可以看到，对于设置和获取资源，我们可以根据自己的喜爱而选择不同的操作方式。但这四种操作方式之间又有一些微妙的区别，例如类成员属性和数组形式不支持指定默认值，使用类成员属性和setter访问器时不能使用非法的变量名称。  

#### (2) 开发-配置-使用模式

在基础入门的各个章节最后，我们都可以看到“扩展你的项目”这样的内容，是因为通过DI统一管理和维护资源外，便可以使用**开发-配置-使用模式**轻松对资源进行替换、升级。开发-配置-使用 模式即：开发实现-配置注册-客户使用模式。此模式能够有效解决框架固有功能与日益多样化项目开发需求之间的鸿沟。  

### 3.1.3 让资源更可控

## 3.2 PEAR包命名规范下的自动加载

