<?php

namespace Mrubiosan\Facade;

use Psr\Container\ContainerInterface;

/**
 * A bridge allowing static calls from the alias to be forwarded to the instance returned by
 * the service locator
 * @author marcrubio
 *
 */
abstract class FacadeAccessor
{
    /**
     * @var ContainerInterface
     */
    private static $serviceLocator;

    /**
     * Prevent misuse. Instances should not be extending this class, or black magic happens.
     * @codeCoverageIgnore Covered by \Mrubiosan\Facade\Tests\Unit\FacadeAccessorTest::testItDisablesConstructor
     */
    final private function __construct()
    {
    }

    /**
     * Sets the service locator
     * @param ContainerInterface $serviceLocator
     */
    final public static function setServiceLocator(ContainerInterface $serviceLocator)
    {
        self::$serviceLocator = $serviceLocator;
    }

    /**
     * Unsets the service locator. Useful for testing or creative minds.
     */
    final public static function unsetServiceLocator()
    {
        self::$serviceLocator = null;
    }

    /**
     * Returns the service from the service locator
     * @return object
     *
     * @throws \LogicException If no service locator has been set
     */
    private static function getService()
    {
        if (!isset(self::$serviceLocator)) {
            throw new \LogicException("Service locator has not been set yet");
        }

        return self::$serviceLocator->get(static::getServiceName());
    }

    abstract public static function getServiceName();

    /**
     * Calls the instance methods
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     *
     * @throws \LogicException
     */
    public static function __callStatic($name, array $arguments)
    {
        return self::getService()->$name(...$arguments);
    }
}
