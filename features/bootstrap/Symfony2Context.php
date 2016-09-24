<?php
use Behat\Behat\Context\Context;
use Mrubiosan\Facade\FacadeAccessor;
use Mrubiosan\Facade\ClassAliaser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Mrubiosan\Facade\Framework\Symfony\FacadeBundle;

class Symfony2Context implements Context
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
     * @var \Silex\Application
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
     * @BeforeScenario
     */
    public function setupScenario()
    {
        Debug::enable();
        $this->app = new \AppKernel('prod', true);
    }
    
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
        $this->app->setConfig(['services' => [$registeredName => ['class' => $type]]]);
        $this->isServiceLocatorSet = true;
    }
    
    /**
     * @Given I have a facade to a service named :serviceName
     */
    public function iHaveAFacadeToAServiceNamed($serviceName)
    {
        $this->facadeClassName = $this->getUniqueClassName();
        $facadeAccessor = 'Mrubiosan\Facade\FacadeAccessor';
        eval("class $this->facadeClassName extends $facadeAccessor { static public function getServiceName() { return '$serviceName'; } }");
    }
    
    /**
     * @Given I have an unimplemented facade
     */
    public function iHaveAnUnimplementedFacade()
    {
        $this->facadeClassName = $this->getUniqueClassName();
        $facadeAccessor = 'Mrubiosan\Facade\FacadeAccessor';
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
    
    protected function getFacadeBundle()
    {
        return new FacadeBundle();
    }
    
    private function bootApp()
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
    
    /**
     * @param string $prefix
     * @return string
     */
    private function getUniqueClassName($prefix = 'Facade')
    {
        return $prefix.str_replace('.', '', uniqid(null, true));
    }
}