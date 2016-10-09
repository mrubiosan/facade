<?php
namespace Mrubiosan\Facade\Framework\Silex2;

use Mrubiosan\Facade\FacadeLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter;
use Mrubiosan\Facade\ClassAliaser;

class FacadeProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $pimple)
    {
        if ($pimple->offsetExists('facade.aliases')) {
            $aliases = $pimple->offsetGet('facade.aliases');
        } else {
            $aliases = null;
        }

        $facadeServiceLocator = new ArrayAccessAdapter($pimple);
        FacadeLoader::init($facadeServiceLocator, $aliases);
    }

    public function boot(Application $app)
    {
        if ($app->offsetExists('facade.aliases')) {
            $aliases = $app->offsetGet('facade.aliases');
            ClassAliaser::register($aliases);
        }
    }
}
