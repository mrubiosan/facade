<?php
namespace Mrubiosan\Facade\Framework\Symfony;

use Mrubiosan\Facade\FacadeServiceLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceLocatorAdapter implements FacadeServiceLocatorInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get($name)
    {
        return $this->container->get($name);
    }
}
