<?php
/**
 * PhpUnderControl_DomainSPushWeek_Test
 *
 * 针对 ../../Domain/SPush/Week.php Domain_SPush_Week 类的PHPUnit单元测试
 *
 * @author: dogstar 20150712
 */

require_once dirname(__FILE__) . '/../test_env.php';

if (!class_exists('Domain_SPush_Week')) {
    require dirname(__FILE__) . '/../../Domain/SPush/Week.php';
}

class PhpUnderControl_DomainSPushWeek_Test extends PHPUnit_Framework_TestCase
{
    public $domainSPushWeek;

    protected function setUp()
    {
        parent::setUp();

        $this->domainSPushWeek = new Domain_SPush_Week();
    }

    protected function tearDown()
    {
    }

    public function testPush() {
        DI()->notorm->spush_record->where('user_id', 187)->where("type like '%week%'")->delete();

        $UUID = 'AAAD56B5460339234A4A2492680171A88818B96B8D8DA687FB';
        $rs = $this->domainSPushWeek->push($UUID);

        $this->assertSame(0, $rs);
    }

}
