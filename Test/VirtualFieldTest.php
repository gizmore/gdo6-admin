<?php
namespace GDO\Admin\Test;

use PHPUnit\Framework\TestCase;
use GDO\Admin\Method\Permissions;
use GDO\Tests\MethodTest;
use GDO\Core\GDO_Module;

final class VirtualFieldTest extends TestCase
{
    public function testVirtualFields()
    {
        $result = MethodTest::make()->method(Permissions::make())->json()->execute();
        var_dump($result);
    }
    
    public function testCustomField()
    {
        $mod = GDO_Module::blank();
    }
    
}
