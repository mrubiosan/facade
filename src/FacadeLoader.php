<?php
namespace Mrubiosan\Facade;

/**
 * Subscribes to spl_autoload and creates the class aliases on demand
 * @author marcrubio
 *
 */
class FacadeLoader {
    
    /**
     * Aliased classes. The alias as key, and the facade class as value
     * @var array
     */
    private $aliases = [];
    
    /**
     * Constructor
     * @param FacadeServiceLocatorInterface $serviceLocator
     * @param array $aliases
     */
    public function __construct(FacadeServiceLocatorInterface $serviceLocator, array $aliases = null)
    {
        isset($aliases) and $this->aliases = $aliases;
        FacadeAccessor::setServiceLocator($serviceLocator);
        spl_autoload_register(function($className) {
            if (isset($this->aliases[$className])) {
                $facadeClass = $this->aliases[$className];
                class_alias($facadeClass, $className);
            }
        });
    }
}