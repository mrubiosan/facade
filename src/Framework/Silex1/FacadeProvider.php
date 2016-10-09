<?php
namespace Mrubiosan\Facade\Framework\Silex1;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter;
use Mrubiosan\Facade\FacadeAccessor;
use Mrubiosan\Facade\ClassAliaser;

class FacadeProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $facadeServiceLocator = new ArrayAccessAdapter($app);
        FacadeAccessor::setServiceLocator($facadeServiceLocator);
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        if ($app->offsetExists('facade.aliases')) {
            $aliases = $app->offsetGet('facade.aliases');
            ClassAliaser::register($aliases);
        }
    }
}
