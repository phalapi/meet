<?php
/**
 * PhpUnderControl_ApiWelcome_Test
 *
 * 针对 ../Api/Welcome.php Api_Welcome 类的PHPUnit单元测试
 *
 * @author: dogstar 20170509
 */

require_once dirname(__FILE__) . '/../test_env.php';

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


    /**
     * @group testGetRules
     */ 
    public function testGetRules()
    {
        $rs = $this->apiWelcome->getRules();
    }

    /**
     * @group testSay
     */ 
    public function testSay()
    {
        $rs = $this->apiWelcome->say();
    }

}
