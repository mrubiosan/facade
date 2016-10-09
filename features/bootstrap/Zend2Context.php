<?php
use Zend\Mvc\Application;
use Mrubiosan\Facade\Framework\Zend2\FacadeBootstrapListener;

class Zend2Context extends FrameworkContext
{
    /**
     * @var array
     */
    protected $services = [];
    
    /**
     * @var Zend\Mvc\Application
     */
    protected $app;
    
    /**
     * @var boolean
     */
    protected $booted = false;

    protected function registerService($name, $type)
    {
        $this->services[$name] = $type;
    }

    protected function getFacadeListener($aliases)
    {
        return new FacadeBootstrapListener($aliases);
    }
    
    protected function bootApp()
    {
        if (!$this->booted) {
            $config = [
                'modules' => [],
                'module_listener_options' => []
            ];
            if ($this->isServiceLocatorSet) {
                $config['listeners'] = ['facade-listener'];
                $config['service_manager'] = [
                    'invokables' => $this->services,
                    'factories' => [
                        'facade-listener' => function() {
                            return $this->getFacadeListener($this->aliases);
                        }
                    ]
                ];
            }
            
            $this->app = Zend\Mvc\Application::init($config);
            $this->booted = true;
        }
    }
}
