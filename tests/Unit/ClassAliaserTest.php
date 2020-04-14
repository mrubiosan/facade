<?php

namespace Mrubiosan\Facade\Tests\Unit;

use Mrubiosan\Facade\ClassAliaser;
use Mrubiosan\Facade\Tests\Stub\FooFacade;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Mrubiosan\Facade\ClassAliaser
 */
class ClassAliaserTest extends TestCase
{
    protected function tearDown()
    {
        ClassAliaser::unregister();
    }

    public function testItAliasesClasses()
    {
        ClassAliaser::register([
            'aliaserBaz' => FooFacade::class
        ]);
        $this->assertTrue(is_a('aliaserBaz', FooFacade::class, true));
    }

    /**
     * @depends testItAliasesClasses
     */
    public function testItCatchesCircularAliases()
    {
        ClassAliaser::register([
            'selfReferencing' => 'selfReferencing'
        ]);
        $this->expectException(\LogicException::class);
        is_a('selfReferencing', 'selfReferencing', true);
    }

    public function testItBailsWithEmptyAliases()
    {
        $previousAutoloaders = spl_autoload_functions();
        ClassAliaser::register([]);
        $currentAutoloaders = spl_autoload_functions();
        $this->assertEquals($previousAutoloaders, $currentAutoloaders);
    }

    /**
     * @depends testItAliasesClasses
     */
    public function testItUnregistersAliases()
    {
        ClassAliaser::register([
            'aliaserUnregisterBaz' => FooFacade::class
        ]);
        ClassAliaser::unregister();
        $this->assertFalse(is_a('aliaserUnregisterBaz', FooFacade::class, true));
    }
}
