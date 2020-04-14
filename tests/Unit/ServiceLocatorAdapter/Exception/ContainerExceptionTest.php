<?php

namespace Mrubiosan\Facade\Tests\Unit\ServiceLocatorAdapter\Exception;

use Mrubiosan\Facade\ServiceLocatorAdapter\Exception\ContainerException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

class ContainerExceptionTest extends TestCase
{
    public function testTypes()
    {
        $testSubject = new ContainerException();
        $this->assertInstanceOf(\Throwable::class, $testSubject);
        $this->assertInstanceOf(ContainerExceptionInterface::class, $testSubject);
    }
}
