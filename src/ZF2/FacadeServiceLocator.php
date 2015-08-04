<?php
namespace Mrubiosan\Facade\ZF2;

use Mrubiosan\Facade\FacadeServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Implementation that decorates the zend service manager
 * @author marcrubio
 *
 */
class FacadeServiceLocator implements FacadeServiceLocatorInterface {
    
    /**
     * 
     * @var ServiceLocatorInterface
     */
    private $zendServiceLocator;
    
    public function __construct(ServiceLocatorInterface $zendServiceLocator)
    {
        $this->zendServiceLocator = $zendServiceLocator;
    }
    
    public function get($name)
    {
        return $this->zendServiceLocator->get($name);
    }
}