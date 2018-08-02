# facade
Facade pattern for PHP [![Build Status](https://travis-ci.org/mrubiosan/facade.svg?branch=master)](https://travis-ci.org/mrubiosan/facade)

Facade pattern as seen in the Laravel framework. This library allows calling a method
on an instance through a static class. However, you are retrieving such instance through a service locator. In that sense,
you're coupling your code with the facade, but the underlying implementation can change over time, as opposed to using singletons for example.

**Usage example**
```php
//First declare your facade class
class Geolocation extends \Mrubiosan\Facade\FacadeAccessor
{
    static public function getServiceName()
    {
       return 'geolocation-service';
    }
}

//Now the instance will be fetched from your service locator, and the method called
\Geolocation::getCountry('127.0.0.1');
```

## Wiring it up

First create an adapter for your service locator. You have three options available:
* ```Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter```: if you're using pimple you can use this.
* ```Mrubiosan\Facade\ServiceLocatorAdapter\CallableAdapter```: you can provide a callable parameter that will receive the service name it should retrieve.
* Implement ```Mrubiosan\Facade\FacadeServiceLocatorInterface```

Afterwards, you just need to make this call
```php
//Aliases are always optional
Mrubiosan\Facade\FacadeLoader::init($yourServiceLocator, ['Alias1' => 'To\Fully\Qualified\Class']);
```
