<?php
use Behat\Behat\Context\Context;
use Mrubiosan\Facade\FacadeAccessor;
use Mrubiosan\Facade\ClassAliaser;
use Zend\Mvc\Application;
use Mrubiosan\Facade\Framework\Zend3\FacadeBootstrapListener;

class Zend3Context implements Context
{
    use ExceptionTrait;
    
    /**
     * @var string|null
     */
    private $lastAliasedClass = null;
    
    /**
     * @var string|null
     */
    private $facadeClassName = null;
    
    /**
     * @var mixed|null
     */
    private $facadeReturnValue = null;
    
    /**
     * @var array
     */
    private $aliases = [];
    
    /**
     * @var array
     */
    private $services = [];
    
    /**
     * @var Zend\Mvc\Application
     */
    private $app;
    
    /**
     * @var boolean
     */
    private $booted = false;

    /**
     * @var boolean
     */
    private $isServiceLocatorSet = false;

    /**
     * @AfterScenario
     */
    public function afterScenario()
    {
        ClassAliaser::unregister();
        FacadeAccessor::unsetServiceLocator();
    }

    /**
     * @Given I have registered an alias named :alias and mapped to :class
     */
    public function iHaveRegisteredAnAliasNamedAndMappedTo($alias, $class)
    {
        if (!class_exists($class, true)) {
            eval("class $class {}");
        }
        
        $this->aliases[$alias] = $class;
        $this->lastAliasedClass = $alias;
    }
    
    /**
     * @Given I have registered an alias named :alias and mapped to non existing :class
     */
    public function iHaveRegisteredAnAliasNamedAndMappedToNonExisting($alias, $class)
    {
        assert(!class_exists($class), "Class $class should not exist");
    
        $this->aliases[$alias] = $class;
        $this->lastAliasedClass = $alias;
    }
    
    /**
     * @When I try to use that class
     */
    public function iTryToUseThatClass()
    {
        $this->isServiceLocatorSet = true;
        $this->bootApp();

        $this->callAndCatchException(function() {
            class_exists($this->lastAliasedClass, true);
        });
    }
    
    /**
     * @Then I should have that class available
     */
    public function iShouldHaveThatClassAvailable()
    {
        assert(class_exists($this->lastAliasedClass, false), "Class $this->lastAliasedClass should exist");
    }
    
    /**
     * @Given I set a service locator with an instance of :type registered as :registeredName
     */
    public function iSetAServiceLocatorWithAnInstanceOfRegisteredAs($type, $registeredName)
    {
        $this->services[$registeredName] = $type;
        $this->isServiceLocatorSet = true;
    }
    
    /**
     * @Given I have a facade to a service named :serviceName
     */
    public function iHaveAFacadeToAServiceNamed($serviceName)
    {
        $this->facadeClassName = $this->getUniqueClassName();
        $facadeAccessor = FacadeAccessor::class;
        eval("class $this->facadeClassName extends $facadeAccessor { static public function getServiceName() { return '$serviceName'; } }");
    }
    
    /**
     * @Given I have an unimplemented facade
     */
    public function iHaveAnUnimplementedFacade()
    {
        $this->facadeClassName = $this->getUniqueClassName();
        $facadeAccessor = FacadeAccessor::class;
        eval("class $this->facadeClassName extends $facadeAccessor {}");
    }
    
    /**
     * @When I call the method :methodName on that facade
     */
    public function iCallTheMethodOnThatFacade($methodName)
    {
        $this->bootApp();
        $this->callAndCatchException(function() use($methodName) {
            $facadeClass = $this->facadeClassName;
            $this->facadeReturnValue = $facadeClass::$methodName();
        });
    }
    
    /**
     * @Then I get :value as the facade return value
     */
    public function iGetAsTheFacadeReturnValue($value)
    {
        assert(isset($this->facadeReturnValue) && $this->facadeReturnValue == $value, "The facade return value does not match '$value', got '$this->facadeReturnValue'");
    }
    
    protected function getFacadeListener($aliases)
    {
        return new FacadeBootstrapListener($aliases);
    }
    
    private function bootApp()
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
    
    /**
     * @param string $prefix
     * @return string
     */
    private function getUniqueClassName($prefix = 'Facade')
    {
        return $prefix.str_replace('.', '', uniqid(null, true));
    }
}