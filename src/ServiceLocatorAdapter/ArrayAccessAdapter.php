<?php

namespace Mrubiosan\Facade\ServiceLocatorAdapter;

use Mrubiosan\Facade\ServiceLocatorAdapter\Exception\ContainerException;
use Mrubiosan\Facade\ServiceLocatorAdapter\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class ArrayAccessAdapter implements ContainerInterface
{
    /**
     * @var \ArrayAccess
     */
    private $container;

    public function __construct(\ArrayAccess $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function has($id)
    {
        return $this->container->offsetExists($id);
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException("No entry '$id' found");
        }

        try {
            return $this->container->offsetGet($id);
        } catch (\Throwable $e) {
            throw new ContainerException("Could not retrieve entry '$id'", 0, $e);
        }
    }
}
