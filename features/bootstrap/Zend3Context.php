<?php
use Zend\Mvc\Application;
use Mrubiosan\Facade\Framework\Zend3\FacadeBootstrapListener;

class Zend3Context extends Zend2Context
{
    protected function getFacadeListener($aliases)
    {
        return new FacadeBootstrapListener($aliases);
    }
    
    protected function bootApp()
    {
        if (!$this->booted) {
            $config = [
                'modules' => [
                    'Zend\Router',
                    'Zend\Validator'
                ],
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
