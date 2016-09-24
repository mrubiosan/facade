<?php

namespace spec\Mrubiosan\Facade\ServiceLocatorAdapter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayAccessAdapterSpec extends ObjectBehavior
{
    function let(\ArrayAccess $container) {
        $this->beConstructedWith($container);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter');
    }
    
    function it_delegates_to_container(\ArrayAccess $container)
    {
        $serviceName = 'dummy';
        $retval = 'foo';
        $container
            ->offsetGet($serviceName)
            ->willReturn($retval)
            ->shouldBeCalled();
        $this->get($serviceName)->shouldBe($retval);
    }
}
