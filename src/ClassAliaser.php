<?php

namespace Mrubiosan\Facade;

class ClassAliaser
{
    /**
     * @var array
     */
    private static $aliases = [];

    /**
     * @var callable
     */
    private static $autoloadFn;

    /**
     * Registers a class autoloader which will create the given class aliases on demand.
     * @param array $aliases Aliased classes. The alias as key, and the facade class as value
     */
    public static function register(array $aliases)
    {
        if (!$aliases) {
            return;
        }

        if (!isset(self::$autoloadFn)) {
            $classAliases = &self::$aliases;
            self::$autoloadFn = function ($className) use (&$classAliases) {
                if (isset($classAliases[$className])) {
                    if (strtolower($classAliases[$className]) === strtolower($className)) {
                        throw new \LogicException("Class alias is referencing the alias itself");
                    }
                    $facadeClass = $classAliases[$className];
                    class_alias($facadeClass, $className);
                }
            };

            spl_autoload_register(self::$autoloadFn);
        }

        self::$aliases = array_merge(self::$aliases, $aliases);
    }

    /**
     * Unregisters the aliases and class autoloading function
     */
    public static function unregister()
    {
        if (isset(self::$autoloadFn)) {
            spl_autoload_unregister(self::$autoloadFn);
            self::$autoloadFn = null;
        }

        self::$aliases = [];
    }
}
