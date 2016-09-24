<?php
namespace Mrubiosan\Facade\ServiceLocatorAdapter;

use Mrubiosan\Facade\FacadeServiceLocatorInterface;

class CallableAdapter implements FacadeServiceLocatorInterface
{
    private $serviceLocatorCallable;

    public function __construct(callable $serviceLocatorGetter)
    {
        $this->serviceLocatorCallable = $serviceLocatorGetter;
    }

    public function get($name)
    {
        $callable = $this->serviceLocatorCallable;
        return $callable($name);
    }
}
