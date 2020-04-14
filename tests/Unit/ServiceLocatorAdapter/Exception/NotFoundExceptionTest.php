<?php

namespace Mrubiosan\Facade\Tests\Unit\ServiceLocatorAdapter\Exception;

use Mrubiosan\Facade\ServiceLocatorAdapter\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundExceptionTest extends TestCase
{
    public function testTypes()
    {
        $testSubject = new NotFoundException();
        $this->assertInstanceOf(\Throwable::class, $testSubject);
        $this->assertInstanceOf(NotFoundExceptionInterface::class, $testSubject);
    }
}
