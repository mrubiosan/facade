<?php
namespace Mrubiosan\Facade;

/**
 * A bridge allowing static calls from the alias to be forwarded to the instance returned by
 * the service locator
 * @author marcrubio
 *
 */
abstract class FacadeAccessor
{

    /**
     * @var FacadeServiceLocatorInterface
     */
    static private $serviceLocator;
    
    /**
     * Sets the service locator
     * @param FacadeServiceLocatorInterface $serviceLocator
     */
    final static public function setServiceLocator(FacadeServiceLocatorInterface $serviceLocator)
    {
        self::$serviceLocator = $serviceLocator;
    }
    
    /**
     * Returns the service from the service locator
     * @return object
     */
    static private function getService()
    {
        return self::$serviceLocator->get(static::getServiceName());
    }

    /**
     * This should be the associated service name to this facade.
     * This value will be passed into the service locator.
     * @return string The name of the associated service
     */
    abstract static public function getServiceName();
    
    /**
     * Calls the instance methods
     * @param string $name
     * @param array $arguments
     */
    static public function __callStatic($name, array $arguments)
    {
        $callable = [
            self::getService(),
            $name
        ];
        
        return call_user_func_array($callable, $arguments);
    }
}