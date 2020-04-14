<?php

namespace Mrubiosan\Facade\Tests\Unit;

use Mrubiosan\Facade\FacadeAccessor;
use Mrubiosan\Facade\Tests\Stub\FooFacade;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Mrubiosan\Facade\FacadeAccessor
 */
class FacadeAccessorTest extends TestCase
{
    protected function tearDown()
    {
        FacadeAccessor::unsetServiceLocator();
    }

    public function testItRequiresAServiceLocator()
    {
        $this->expectException(\LogicException::class);
        FooFacade::anything();
    }

    public function testItFetchesServiceAndProxies()
    {
        $serviceLocatorMock = $this->prophesize(ContainerInterface::class);
        FacadeAccessor::setServiceLocator($serviceLocatorMock->reveal());

        $serviceMock = $this->prophesize(\DateTime::class);
        $serviceLocatorMock->get('foo')
            ->willReturn($serviceMock);

        $serviceMock->format('Y-m-d')
            ->shouldBeCalled()
            ->willReturn('2000-01-01');

        $this->assertEquals('2000-01-01', FooFacade::format('Y-m-d'));
    }

    /**
     * @covers \Mrubiosan\Facade\FacadeAccessor::__construct
     */
    public function testItDisablesConstructor()
    {
        $this->expectException(\Error::class);
        new FooFacade();
    }
}
