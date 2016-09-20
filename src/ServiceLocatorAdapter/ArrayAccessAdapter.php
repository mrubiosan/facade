<?php
namespace Mrubiosan\Facade\ServiceLocatorAdapter;

use Mrubiosan\Facade\FacadeServiceLocatorInterface;

class ArrayAccessAdapter implements FacadeServiceLocatorInterface
{
    /**
     * @var \ArrayAccess
     */
    private $container;
    
    public function __construct(\ArrayAccess $container)
    {
        $this->container = $container;
    }
    
    public function get($name)
    {
        return $this->container->offsetGet($name);
    }
}