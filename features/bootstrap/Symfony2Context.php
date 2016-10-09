<?php
use Symfony\Component\Debug\Debug;
use Mrubiosan\Facade\Framework\Symfony\FacadeBundle;

class Symfony2Context extends FrameworkContext
{
    /**
     * @var \AppKernel
     */
    private $app;

    /**
     * @var boolean
     */
    private $booted = false;

    /**
     * @BeforeScenario
     */
    public function setupScenario()
    {
        Debug::enable();
        $this->app = new \AppKernel('prod', true);
    }

    protected function getFacadeBundle()
    {
        return new FacadeBundle();
    }

    protected function registerService($name, $type)
    {
        $this->app->setConfig(['services' => [$name => ['class' => $type]]]);
    }


    protected function bootApp()
    {
        if (!$this->booted) {
            $this->app->setConfig(['parameters' => ['facade.aliases' => $this->aliases]]);
            if ($this->isServiceLocatorSet) {
                $this->app->addBundle($this->getFacadeBundle());
                //$this->app->register($this->getFacadeProvider(), ['facade.aliases' => $this->aliases]);
            }

            $this->app->boot();//handle(Request::createFromGlobals());
            $this->booted = true;
        }
    }
}
