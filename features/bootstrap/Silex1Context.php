<?php
use Mrubiosan\Facade\Framework\Silex1\FacadeProvider;
use Symfony\Component\HttpFoundation\Request;

class Silex1Context extends FrameworkContext
{
    /**
     * @var boolean
     */
    private $booted = false;

    /**
     * @BeforeScenario
     */
    public function setupSilexApplication()
    {
        $this->app = new \Silex\Application();
    }

    protected function getFacadeProvider()
    {
        return new FacadeProvider();
    }

    protected function registerService($name, $type)
    {
        $this->app[$name] = new $type;
    }

    protected function bootApp()
    {
        if (!$this->booted) {
            if ($this->isServiceLocatorSet) {
                $this->app->register($this->getFacadeProvider(), ['facade.aliases' => $this->aliases]);
            }

            $this->app->handle(Request::createFromGlobals());
            $this->booted = true;
        }
    }
}
