<?php
/**
 * PhpUnderControl_ApiComment_Test
 *
 * 针对 ../Api/Comment.php Api_Comment 类的PHPUnit单元测试
 *
 * @author: dogstar 20170518
 */

require_once dirname(__FILE__) . '/../test_env.php';

if (!class_exists('Api_Comment')) {
    require dirname(__FILE__) . '/../Api/Comment.php';
}

class PhpUnderControl_ApiComment_Test extends PHPUnit_Framework_TestCase
{
    public $apiComment;

    protected function setUp()
    {
        parent::setUp();

        $this->apiComment = new Api_Comment();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testGetRules
     */ 
    public function testGetRules()
    {
        $rs = $this->apiComment->getRules();
    }

    /**
     * @group testGet
     */ 
    public function testGet()
    {
        // Step 1. 构建
        $url = 'service=Comment.Get';
        $params = array('id' => 1);

        // Step 2. 执行
        $rs = PhalApi_Helper_TestRunner::go($url, $params);

        // Step 3. 验证
        $this->assertEquals(1, $rs['id']);
        $this->assertArrayHasKey('content', $rs);
    }

    public function testGetAgain()
    {
        $url = 'service=Comment.Get';
        $params = array('id' => 2);

        $rs = PhalApi_Helper_TestRunner::go($url, $params);

        $this->assertEquals(2, $rs['id']);
        $this->assertArrayHasKey('content', $rs);
    }

    public function testGetNotExists()
    {
        $url = 'service=Comment.Get';
        $params = array('id' => 404);

        $rs = PhalApi_Helper_TestRunner::go($url, $params);

        $this->assertSame(array(), $rs);
    }

    /**
     * @expectedException PhalApi_Exception_BadRequest
     */
    public function testGetWithWrongId()
    {
        $url = 'service=Comment.Get';
        $params = array('id' => 'a_wrong_id');

        $rs = PhalApi_Helper_TestRunner::go($url, $params);
    }

    /**
     * @group testAdd
     */ 
    //public function testAdd()
    //{
    //    $rs = $this->apiComment->add();
    //}

    /**
     * @group testUpdate
     */ 
    //public function testUpdate()
    //{
    //    $rs = $this->apiComment->update();
    //}

    /**
     * @group testDelete
     */ 
    //public function testDelete()
    //{
    //    $rs = $this->apiComment->delete();
    //}

}
