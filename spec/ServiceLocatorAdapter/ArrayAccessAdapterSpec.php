<?php

namespace spec\Mrubiosan\Facade\ServiceLocatorAdapter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ArrayAccessAdapterSpec extends ObjectBehavior
{
    function let(\ArrayAccess $container) {
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter');
    }

    function it_reports_whether_it_has_entry(\ArrayAccess $container)
    {
        $container->offsetExists('foo')
            ->willReturn(true, false);

        $this->has('foo')->shouldBe(true);
        $this->has('foo')->shouldBe(false);
    }

    function it_delegates_to_container(\ArrayAccess $container)
    {
        $serviceName = 'dummy';
        $retval = 'foo';
        $container
            ->offsetExists($serviceName)
            ->willReturn(true)
            ->shouldBeCalled();
        $container
            ->offsetGet($serviceName)
            ->willReturn($retval)
            ->shouldBeCalled();

        $this->get($serviceName)->shouldBe($retval);
    }

    function it_throws_exception_when_not_found()
    {
        $this->shouldThrow(NotFoundExceptionInterface::class)->duringGet('missing');
    }

    function it_throws_exception_on_failure_getting_the_entry(\ArrayAccess $container)
    {
        $container->offsetExists('dummy')
            ->shouldBeCalled()
            ->willReturn(true);

        $container->offsetGet('dummy')
            ->shouldBeCalled()
            ->willThrow(\RuntimeException::class);

        $this->shouldThrow(ContainerExceptionInterface::class)->duringGet('dummy');
    }
}
