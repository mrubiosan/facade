<?php
namespace Mrubiosan\Facade\ServiceLocatorAdapter;

use Mrubiosan\Facade\FacadeServiceLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SymfonyAdapter implements FacadeServiceLocatorInterface
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