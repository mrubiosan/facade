<?php

namespace Mrubiosan\Facade\Tests\Unit\ServiceLocatorAdapter;

use Mrubiosan\Facade\ServiceLocatorAdapter\CallableAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

/**
 * @covers \Mrubiosan\Facade\ServiceLocatorAdapter\CallableAdapter
 */
class CallableAdapterTest extends TestCase
{
    public function testHasStub()
    {
        $testSubject = new CallableAdapter(function () {
        });
        $this->assertTrue($testSubject->has('foo'));
    }

    public function testItGetsExistingService()
    {
        $callable = function ($id) {
            $this->assertEquals('foo', $id);
            return 'bar';
        };

        $testSubject = new CallableAdapter($callable);
        $this->assertEquals('bar', $testSubject->get('foo'));
    }

    public function testItThrowsContainerException()
    {
        $callable = function ($id) {
            $this->assertEquals('foo', $id);
            throw new \RuntimeException();
        };

        $testSubject = new CallableAdapter($callable);
        $this->expectException(ContainerExceptionInterface::class);
        $testSubject->get('foo');
    }
}
