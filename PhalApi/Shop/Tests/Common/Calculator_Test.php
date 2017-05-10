<?php
/**
 * PhpUnderControl_Calculator_Test
 *
 * 针对 ./Calculator.php Calculator 类的PHPUnit单元测试
 *
 * @author: dogstar 20170510
 */

//require_once dirname(__FILE__) . '/test_env.php';

if (!class_exists('Calculator')) {
    require dirname(__FILE__) . '/./Calculator.php';
}

class PhpUnderControl_Calculator_Test extends PHPUnit_Framework_TestCase
{
    public $calculator;

    protected function setUp()
    {
        parent::setUp();

        $this->calculator = new Calculator();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testAdd
     */ 
    public function testAdd()
    {
        $left = '';
        $right = '';

        $rs = $this->calculator->add($left, $right);

        $this->assertTrue(is_int($rs));

    }

    /**
     * @group testAdd
     */ 
    public function testAddCase0()
    {
        $rs = $this->calculator->add(1,1);

        $this->assertEquals(2, $rs);
    }

    /**
     * @group testAdd
     */ 
    public function testAddCase1()
    {
        $rs = $this->calculator->add(-10,5);

        $this->assertEquals(-5, $rs);
    }

}
