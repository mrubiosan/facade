<?php

namespace Mrubiosan\Facade\Tests\Integration;

use Mrubiosan\Facade\ClassAliaser;
use Mrubiosan\Facade\FacadeAccessor;
use Mrubiosan\Facade\FacadeLoader;
use Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter;
use Mrubiosan\Facade\Tests\Stub\FooFacade;
use PHPUnit\Framework\TestCase;

/**
 * Class FacadeLoaderTest
 */
class FacadeLoaderTest extends TestCase
{
    protected function tearDown()
    {
        FacadeAccessor::unsetServiceLocator();
        ClassAliaser::unregister();
    }

    /**
     * @covers \Mrubiosan\Facade\FacadeLoader
     */
    public function testItSetsUpFacade()
    {
        $dateTime = new \DateTime('2000-01-01 00:00:00');
        $container = new \ArrayIterator([
            'foo' => $dateTime
        ]);

        $containerAdapter = new ArrayAccessAdapter($container);
        FacadeLoader::init($containerAdapter, ['baz' => FooFacade::class]);

        $this->assertEquals($dateTime->getTimestamp(), FooFacade::getTimestamp());
        $this->assertEquals($dateTime->getTimestamp(), \baz::getTimestamp());
    }
}
