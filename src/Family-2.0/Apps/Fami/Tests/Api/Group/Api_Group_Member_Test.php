<?php
/**
 * PhpUnderControl_ApiGroupMember_Test
 *
 * 针对 ../../../Api/Group/Member.php Api_Group_Member 类的PHPUnit单元测试
 *
 * @author: dogstar 20150403
 */

require_once dirname(__FILE__) . '../../../test_env.php';

if (!class_exists('Api_Group_Member')) {
    require dirname(__FILE__) . '/../../../Api/Group/Member.php';
}

class PhpUnderControl_ApiGroupMember_Test extends PHPUnit_Framework_TestCase
{
    public $apiGroupMember;

    protected function setUp()
    {
        parent::setUp();

        $this->apiGroupMember = new Api_Group_Member();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testGetRules
     */ 
    public function testGetRules()
    {
        $rs = $this->apiGroupMember->getRules();
    }

    /**
     * @group testGetList
     */ 
    public function testGetList()
    {
        //Step 1. 构建请求URL
        $url = 'service=Group_Member.GetList&client=ios&app_key=64c736b17271a8c4e7a6b07a065a951a&device_agent=iPhone Simulator&version=3.2&token=D6113676E92E47EAE1F8BFC709A7149B4AC591DBB238A1C1F90E1B2D0E3E0063&sign=517821a964ba45e4efb041617fcdf7bc&UUID=AAAD56B5460339234A4A2492680171A88818B96B8D8DA687FB&debug=1&page=1&perpage=10&group_id=39&__debug__=1';

        //Step 2. 执行请求
        $rs = PhalApi_Helper_TestRunner::go($url);
        // var_dump($rs);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);
        $this->assertArrayHasKey('members', $rs);

        $this->assertEquals(0, $rs['code']);
        $this->assertTrue(is_numeric($rs['member_num']));
    }

    public function testQuitGroup()
    {
        //Step 1. 构建请求URL
        $url = 'app_key=mini&client=ios&version=1.0.0&service=Group_Member.QuitGroup&sign=8c3b4&__debug__=1&UUID=A3D779AF491F86ECFCD22F05CD6A9D1C9F8719CFE4BCEA06B0&group_id=39';

        //Step 2. 执行请求
        $rs = PhalApi_Helper_TestRunner::go($url);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);

        $this->assertEquals(0, $rs['code']);

        //创建者不能离开
        $url = 'app_key=mini&client=ios&version=1.0.0&service=Group_Member.QuitGroup&sign=8c3b4&__debug__=1&UUID=AAAD56B5460339234A4A2492680171A88818B96B8D8DA687FB&group_id=39';
        $rs = PhalApi_Helper_TestRunner::go($url);
        $this->assertEquals(1, $rs['code']);
    }

    public function testRemoveGroupMember()
    {
        //Step 1. 构建请求URL
        $url = 'app_key=mini&client=ios&version=1.0.0&service=Group_Member.RemoveGroupMember&sign=8c3b4&__debug__=1&UUID=AAAD56B5460339234A4A2492680171A88818B96B8D8DA687FB&group_id=39&other_UUID=A3D779AF491F86ECFCD22F05CD6A9D1C9F8719CFE4BCEA06B0';

        //Step 2. 执行请求
        $rs = PhalApi_Helper_TestRunner::go($url);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);

        $this->assertEquals(0, $rs['code']);

        //非创建者
        $url = 'app_key=mini&client=ios&version=1.0.0&service=Group_Member.RemoveGroupMember&sign=8c3b4&__debug__=1&UUID=A3D779AF491F86ECFCD22F05CD6A9D1C9F8719CFE4BCEA06B0&group_id=39&other_UUID=A3D779AF491F86ECFCD22F05CD6A9D1C9F8719CFE4BCEA06B0';
        $rs = PhalApi_Helper_TestRunner::go($url);
        $this->assertEquals(1, $rs['code']);
    }

    /**
     * @group testJoinGroup
     */ 
    public function testJoinGroup()
    {
        DI()->notorm->group_member->where('user_id', 110)->delete();

        // Step 1. 构建请求URL
        $url = 'app_key=mini&client=ios&version=1.0.0&service=Group_Member.JoinGroup&sign=8c3b4&__debug__=1&UUID=A3D779AF491F86ECFCD22F05CD6A9D1C9F8719CFE4BCEA06B0&group_num=1763&group_pwd=1111';

        // Step 2. 执行请求
        $rs = PhalApi_Helper_TestRunner::go($url);

        // Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);
        $this->assertArrayHasKey('group_id', $rs);

        $this->assertEquals(0, $rs['code']);
        $this->assertGreaterThan(0, $rs['group_id']);

        // 不能再次加入
        $rs = PhalApi_Helper_TestRunner::go($url);
        $this->assertEquals(3, $rs['code']);

        // 家庭圈密码错误
        $url = 'app_key=mini&client=ios&version=1.0.0&service=Group_Member.JoinGroup&sign=8c3b4&__debug__=1&UUID=A3D779AF491F86ECFCD22F05CD6A9D1C9F8719CFE4BCEA06B0&group_num=1763&group_pwd=1112';
        $rs = PhalApi_Helper_TestRunner::go($url);
        $this->assertEquals(2, $rs['code']);

        // 家庭圈不存在
        $url = 'app_key=mini&client=ios&version=1.0.0&service=Group_Member.JoinGroup&sign=8c3b4&__debug__=1&UUID=A3D779AF491F86ECFCD22F05CD6A9D1C9F8719CFE4BCEA06B0&group_num=4444&group_pwd=1111';
        $rs = PhalApi_Helper_TestRunner::go($url);
        $this->assertEquals(1, $rs['code']);
    }
}
