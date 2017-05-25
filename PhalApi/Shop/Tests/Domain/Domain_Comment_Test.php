<?php
/**
 * PhpUnderControl_DomainComment_Test
 *
 * 针对 ../Domain/Comment.php Domain_Comment 类的PHPUnit单元测试
 *
 * @author: dogstar 20170518
 */

require_once dirname(__FILE__) . '/../test_env.php';

if (!class_exists('Domain_Comment')) {
    require dirname(__FILE__) . '/../Domain/Comment.php';
}

class PhpUnderControl_DomainComment_Test extends PHPUnit_Framework_TestCase
{
    public $domainComment;

    protected function setUp()
    {
        parent::setUp();

        $this->domainComment = new Domain_Comment();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testGet
     */ 
    public function testGet()
    {
        $id = '1';

        $rs = $this->domainComment->get($id);

        $this->assertNotEmpty($rs);
    }

    public function testGetNone()
    {
        $id = 404;

        $rs = $this->domainComment->get($id);

        $this->assertEmpty($rs);
    }

}
