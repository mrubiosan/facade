<?php

namespace Mrubiosan\Facade\Tests\Unit\ServiceLocatorAdapter;

use Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @covers \Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter
 */
class ArrayAccessAdapterTest extends TestCase
{
    private $testSubject;

    private $arrayAccessMock;

    protected function setUp()
    {
        $this->arrayAccessMock = $this->prophesize(\ArrayAccess::class);
        $this->testSubject = new ArrayAccessAdapter($this->arrayAccessMock->reveal());
    }

    /**
     * @dataProvider hasServiceProvider
     */
    public function testHas($hasService)
    {
        $this->arrayAccessMock->offsetExists('foo')
            ->shouldBeCalled()
            ->willReturn($hasService);

        $this->assertEquals($hasService, $this->testSubject->has('foo'));
    }

    public function hasServiceProvider()
    {
        return [
            [true],
            [false]
        ];
    }

    public function testItGetsExistingEntry()
    {
        $this->arrayAccessMock->offsetExists('foo')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->arrayAccessMock->offsetGet('foo')
            ->shouldBeCalled()
            ->willReturn('hello');

        $this->assertEquals('hello', $this->testSubject->get('foo'));
    }

    public function testItThrowsNotFoundException()
    {
        $this->arrayAccessMock->offsetExists('foo')
            ->shouldBeCalled()
            ->willReturn(false);

        $this->expectException(NotFoundExceptionInterface::class);
        $this->testSubject->get('foo');
    }

    public function testItThrowsContainerException()
    {
        $this->arrayAccessMock->offsetExists('foo')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->arrayAccessMock->offsetGet('foo')
            ->shouldBeCalled()
            ->willThrow(\Exception::class);

        $this->expectException(ContainerExceptionInterface::class);
        $this->testSubject->get('foo');
    }
}
