<?php
namespace Mrubiosan\Facade\Bootstrap\Silex;

use Mrubiosan\Facade\FacadeLoader;
use Silex\ServiceProviderInterface;
use Silex\Application;
use Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter;

class Silex1Provider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        if ($app->offsetExists('facade.aliases')) {
            $aliases = $app->offsetGet('facade.aliases');
        } else {
            $aliases = null;
        }
        
        $facadeServiceLocator = new ArrayAccessAdapter($app);
        new FacadeLoader($facadeServiceLocator, $aliases);
    }
    
    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app) {}
}
