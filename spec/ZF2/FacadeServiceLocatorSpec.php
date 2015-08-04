<?php

namespace spec\Mrubiosan\Facade\ZF2;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend\ServiceManager\ServiceLocatorInterface;

class FacadeServiceLocatorSpec extends ObjectBehavior
{
    function let(ServiceLocatorInterface $serviceManager)
    {
        $this->beConstructedWith($serviceManager);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Mrubiosan\Facade\ZF2\FacadeServiceLocator');
    }
    
    function it_retrieves_zend_service(ServiceLocatorInterface $serviceManager)
    {
        $serviceName = 'dummy';
        $serviceManager->get($serviceName)->shouldBeCalled();
        $this->get($serviceName);
    }
}
