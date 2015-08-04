<?php
namespace Mrubiosan\Facade\ZF2;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Mrubiosan\Facade\FacadeLoader;

/**
 * Bootstraps the facade system for the zend framework
 * @author marcrubio
 *
 */
class FacadeBootstrapListener implements ListenerAggregateInterface
{

    private $listener;
    
    private $aliases;
    
    public function __construct(array $aliases = [])
    {
        $this->aliases = $aliases;
    }
    
    /*
     * (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listener = $events->attach(MvcEvent::EVENT_BOOTSTRAP, function(MvcEvent $e) {
            if ($this->aliases) {
                $serviceManager = $e->getApplication()->getServiceManager();
                $facadeServiceLocator = new FacadeServiceLocator($serviceManager);
                new FacadeLoader($facadeServiceLocator, $this->aliases);
            }
        }, 2);
    }

    /*
     * (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::detach()
     */
    public function detach(EventManagerInterface $events)
    {
        $events->detach($this->listener);
    }
}