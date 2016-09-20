<?php
namespace Mrubiosan\Facade\Bootstrap\Silex;

use Mrubiosan\Facade\FacadeLoader;
use Pimple\Container;
use Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter;

class Silex2Provider implements \Pimple\ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        if ($pimple->offsetExists('facade.aliases')) {
            $aliases = $pimple->offsetGet('facade.aliases');
        } else {
            $aliases = null;
        }
        
        $facadeServiceLocator = new ArrayAccessAdapter($pimple);
        new FacadeLoader($facadeServiceLocator, $aliases);
    }
}
