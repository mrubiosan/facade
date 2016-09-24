<?php

namespace spec\Mrubiosan\Facade\ServiceLocatorAdapter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
}
