<?php
namespace Mrubiosan\Facade\Framework\Zend3;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Mrubiosan\Facade\FacadeLoader;
use Zend\Stdlib\CallbackHandler;
use Mrubiosan\Facade\Framework\Zend2\ServiceLocatorAdapter;

/**
 * Bootstraps the facade system for the zend framework
 * @author marcrubio
 *
 */
class FacadeBootstrapListener implements ListenerAggregateInterface
{

    /**
     * @var CallbackHandler
     */
    private $listener;

    /**
     * @var array
     */
    private $aliases;

    /**
     * @param array $aliases
     */
    public function __construct(array $aliases = [])
    {
        $this->aliases = $aliases;
    }
    
    /*
     * (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listener = $events->attach(MvcEvent::EVENT_BOOTSTRAP, function(MvcEvent $e) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $facadeServiceLocator = new ServiceLocatorAdapter($serviceManager);
            FacadeLoader::init($facadeServiceLocator, $this->aliases);
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