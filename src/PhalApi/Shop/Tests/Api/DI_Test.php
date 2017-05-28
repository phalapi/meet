<?php

require_once dirname(__FILE__) . '/../test_env.php';

class Simple {
    public function __construct() {
    }
}

class DI_Test extends PHPUnit_Framework_TestCase {

    public function testHere()
    {
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
    }
}
