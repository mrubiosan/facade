<?php

namespace spec\Mrubiosan\Facade\ServiceLocatorAdapter;

use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerExceptionInterface;

class CallableAdapterSpec extends ObjectBehavior
{
    function it_delegates_to_callable()
    {
        $serviceName = 'dummy';
        $retval = uniqid();
        $callable = function($name) use($serviceName, $retval) {
            if ($name === $serviceName) {
                return $retval;
            }
        };

        $this->beConstructedWith($callable);
        $this->get($serviceName)->shouldBe($retval);
    }

    function it_throws_exception_on_failure_getting_the_entry(\ArrayAccess $container)
    {
        $serviceName = 'dummy';
        $retval = uniqid();
        $callable = function($name) use($serviceName, $retval) {
            if ($name === $serviceName) {
                throw new \RuntimeException('Mock callable exception');
            }
        };

        $this->beConstructedWith($callable);
        $this->shouldThrow(ContainerExceptionInterface::class)->duringGet('dummy');
    }

    function it_always_reports_entries_existing()
    {
        $this->beConstructedWith(function() {});
        $this->has('fsdfsdf')->shouldBe(true);
    }
}
