<?php
/**
 * PhpUnderControl_ApiEvent_Test
 *
 * 针对 ./Fun/Api/Event.php Api_Event 类的PHPUnit单元测试
 *
 * @author: dogstar 20170529
 */

require_once dirname(__FILE__) . '/../test_env.php';

if (!class_exists('Api_Event')) {
    require dirname(__FILE__) . '/./Fun/Api/Event.php';
}

class PhpUnderControl_ApiEvent_Test extends PHPUnit_Framework_TestCase
{
    public $apiEvent;

    protected function setUp()
    {
        parent::setUp();

        $this->apiEvent = new Api_Event();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testPost
     */ 
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

    /**
     * @group testSpace
     */ 
    public function testSpace()
    {
        // Step 1. 构建
        $url = 'service=Event.Space&client=ios&version=1.0.1&user_id=1&sign=9897a2670cc329ce8c49a65118ff7287';
        $params = array(
            'perpage' => 5,
            'page' => 1,
            'createtime' => '2017-05-29 59:59:59',
        );
        // echo $url . '&' . http_build_query($params), "\n";

        // Step 2. 执行
        $rs = PhalApi_Helper_TestRunner::go($url, $params);
        // var_dump($rs);

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
        //$this->assertContains('2', $allUid); //2为Angle
    }

    /**
     * @group testOperate
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

}
