<?php
use Mrubiosan\Facade\ClassAliaser;
use Mrubiosan\Facade\FacadeAccessor;
use Behat\Behat\Context\Context;

abstract class FrameworkContext implements Context
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
    protected $aliases = [];

    /**
     * @var boolean
     */
    protected $isServiceLocatorSet = false;

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
        $this->registerService($registeredName, $type);
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
     * @When I call the method :methodName with argument :argument on that facade
     */
    public function iCallTheMethodWithArgumentOnThatFacade($methodName, $argument)
    {
        $this->bootApp();
        $this->callAndCatchException(function() use($methodName, $argument) {
            $facadeClass = $this->facadeClassName;
            $this->facadeReturnValue = $facadeClass::$methodName($argument);
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
    protected function getUniqueClassName($prefix = 'Facade')
    {
        return $prefix.str_replace('.', '', uniqid(null, true));
    }

    abstract protected function bootApp();

    abstract protected function registerService($name, $type);
}
