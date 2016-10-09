<?php
namespace Mrubiosan\Facade;

/**
 * An interface to allow integration with any kind of service locator
 * @author marcrubio
 *
 */
interface FacadeServiceLocatorInterface
{
    /**
     * Retrieves an object instance associated with $name
     * @param string $name
     * @return object
     */
    public function get($name);
}
