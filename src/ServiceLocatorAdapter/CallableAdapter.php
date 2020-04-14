<?php

namespace Mrubiosan\Facade\ServiceLocatorAdapter;

use Mrubiosan\Facade\ServiceLocatorAdapter\Exception\ContainerException;
use Psr\Container\ContainerInterface;

class CallableAdapter implements ContainerInterface
{
    /**
     * @var callable
     */
    private $serviceLocatorCallable;

    public function __construct(callable $serviceLocatorGetter)
    {
        $this->serviceLocatorCallable = $serviceLocatorGetter;
    }

    /**
     * @inheritdoc
     */
    public function has($id)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $callable = $this->serviceLocatorCallable;

        try {
            return $callable($id);
        } catch (\Throwable $e) {
            throw new ContainerException("Could not retrieve entry '$id'", 0, $e);
        }
    }
}
