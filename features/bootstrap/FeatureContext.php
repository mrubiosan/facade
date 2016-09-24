<?php
use Behat\Behat\Context\Context;
use Mrubiosan\Facade\ClassAliaser;
use Mrubiosan\Facade\ServiceLocatorAdapter\CallableAdapter;
use Mrubiosan\Facade\FacadeAccessor;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
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
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
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
        
        ClassAliaser::register([$alias => $class]);
        $this->lastAliasedClass = $alias;
    }
    
    /**
     * @Given I have registered an alias named :alias and mapped to non existing :class
     */
    public function iHaveRegisteredAnAliasNamedAndMappedToNonExisting($alias, $class)
    {
        assert(!class_exists($class), "Class $class should not exist");
    
        ClassAliaser::register([$alias => $class]);
        $this->lastAliasedClass = $alias;
    }
    
    /**
     * @When I unregister the class aliases
     */
    public function iUnregisterTheClassAliases()
    {
        ClassAliaser::unregister();
    }
    
    /**
     * @When I try to use that class
     */
    public function iTryToUseThatClass()
    {
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
     * @Then I should not have that class available
     */
    public function iShouldNotHaveThatClassAvailable()
    {
        assert(!class_exists($this->lastAliasedClass, false), "Class $this->lastAliasedClass should not exist");
    }
    
    /**
     * @Given I set a service locator with an instance of :type registered as :registeredName
     */
    public function iSetAServiceLocatorWithAnInstanceOfRegisteredAs($type, $registeredName)
    {
        $object = new $type();
        $serviceLocatorGetter = function($name) use($registeredName, $object) {
            if ($name === $registeredName) {
                return $object;
            }
        };
        
        $serviceLocator = new CallableAdapter($serviceLocatorGetter);
        FacadeAccessor::setServiceLocator($serviceLocator);
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
    
    /**
     * @param string $prefix
     * @return string
     */
    private function getUniqueClassName($prefix = 'Facade')
    {
        return $prefix.str_replace('.', '', uniqid(null, true));
    }
}
