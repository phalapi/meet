<?php
/**
 * PhpUnderControl_DomainGoods_Test
 *
 * 针对 ../Domain/Goods.php Domain_Goods 类的PHPUnit单元测试
 *
 * @author: dogstar 20170510
 */

require_once dirname(__FILE__) . '/../test_env.php';

if (!class_exists('Domain_Goods')) {
    require dirname(__FILE__) . '/../Domain/Goods.php';
}

class PhpUnderControl_DomainGoods_Test extends PHPUnit_Framework_TestCase
{
    public $domainGoods;

    protected function setUp()
    {
        parent::setUp();

        $this->domainGoods = new Domain_Goods();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testSnapshot
     */ 
    public function testSnapshot()
    {
        $goodsId = '';

        $rs = $this->domainGoods->snapshot($goodsId);

        $this->assertTrue(is_array($rs));

    }

}
