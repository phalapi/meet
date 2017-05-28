<?php
/**
 * PhpUnderControl_ModelComment_Test
 *
 * 针对 ../Model/Comment.php Model_Comment 类的PHPUnit单元测试
 *
 * @author: dogstar 20170518
 */

require_once dirname(__FILE__) . '/../test_env.php';

if (!class_exists('Model_Comment')) {
    require dirname(__FILE__) . '/../Model/Comment.php';
}

class PhpUnderControl_ModelComment_Test extends PHPUnit_Framework_TestCase
{
    public $modelComment;

    protected function setUp()
    {
        parent::setUp();

        $this->modelComment = new Model_Comment();
    }

    protected function tearDown()
    {
    }

    public function testGet()
    {
        
    }

}
