<?php

namespace Mrubiosan\Facade;

use Psr\Container\ContainerInterface;

/**
 * Convenience class for initializing the Facade features
 * @author marcrubio
 *
 */
class FacadeLoader
{
    /**
     * @param ContainerInterface $serviceLocator The service locator that'll fetch the facaded services
     * @param array $aliases Aliased classes for syntax sugar. The alias as key, and the facaded class as value
     */
    public static function init(ContainerInterface $serviceLocator, array $aliases = null)
    {
        FacadeAccessor::setServiceLocator($serviceLocator);
        if ($aliases) {
            ClassAliaser::register($aliases);
        }
    }
}
