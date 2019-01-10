# facade
Facade pattern for PHP

[![Build Status](https://travis-ci.org/mrubiosan/facade.svg?branch=master)](https://travis-ci.org/mrubiosan/facade) [![Maintainability](https://api.codeclimate.com/v1/badges/19db78591f5b9be3e546/maintainability)](https://codeclimate.com/github/mrubiosan/facade/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/19db78591f5b9be3e546/test_coverage)](https://codeclimate.com/github/mrubiosan/facade/test_coverage)

As seen in the Laravel framework.
You can call a method statically, and it will fetch an object from the container and call its method.

It's very useful for unbounded services like Logging. You can call it statically but change the underlying implementation(i.e: for testing).

Also a good compromise when refactoring code that uses static class access.

**Usage example**


```php
//First declare your facade class
namespace MyDummyNameSpace;

class Foo extends \Mrubiosan\Facade\FacadeAccessor
{
    public static function getServiceName()
    {
       return 'foo'; //This is the name of the service in your container
    }
}
```

```php
//Then initialize the facade system
$exampleContainer = new \ArrayObject([
    'foo' => new \DateTime(),
]);
$psrAdaptedContainer = new \Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter($exampleContainer);
\Mrubiosan\Facade\FacadeLoader::init($psrAdaptedContainer, ['FooAlias' => 'MyDummyNameSpace\Foo']);
```

```php
//Ready to use
echo \MyDummyNameSpace\Foo::getTimestamp();
echo \FooAlias::getTimestamp();
```

## Wiring it up

### Step 1
If you're using a PSR11 Container, you can skip this step.
Oterwise you'll need to use an adapter:
* ```Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter```: if you're using pimple you can use this.
* ```Mrubiosan\Facade\ServiceLocatorAdapter\CallableAdapter```: you can provide a callable parameter that will receive the service name it should retrieve.

Or straight implement ```Psr\Container\ContainerInterface```

### Step 2
Initialize the facade system
```php
Mrubiosan\Facade\FacadeLoader::init($psrContainer);
```
