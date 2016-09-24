# facade
Facade pattern for PHP [![Build Status](https://travis-ci.org/mrubiosan/facade.svg?branch=master)](https://travis-ci.org/mrubiosan/facade)

Facade pattern as seen in the Laravel framework. This library allows calling a method
on an instance through a static class. However, you are retrieving such instance through a service locator. In that sense,
you're coupling your code with the facade, but the underlying implementation can change over time, as opposed to using singletons for example.

Offers support out of the box for [Silex1](#silex-1), [Silex2](#silex-2), [Symfony2](#symfony-2--3),
[Symfony3](#symfony-2--3), [Zend Framework 2](#zend-framework-2) and [Zend Framework 3](#zend-framework-3). Or you can easily [roll it on your own](#on-your-own).

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

##Wiring it up

###Silex 1
```php
$app->register(
    new \Mrubiosan\Facade\Framework\Silex1\FacadeProvider(),
    ['facade.aliases' => ['MyAlias' => 'To\My\Fully\Qualified\Class']]
);
```
###Silex 2
```php
$app->register(
    new \Mrubiosan\Facade\Framework\Silex2\FacadeProvider(),
    ['facade.aliases' => ['MyAlias' => 'To\My\Fully\Qualified\Class']]
);
```
###Symfony 2 & 3
On your AppKernel.php file, add this bundle:
```php
new Mrubiosan\Facade\Framework\Symfony\FacadeBundle();
```
If you want to use aliases, in your parameters.yml file add the following entry:
```yml
facade.aliases:
  - Alias1: My\Full\Qualified\Class1
  - Alias2: My\Full\Qualified\Class2
```

###Zend Framework 2
Register ```Mrubiosan\Facade\Framework\Zend2\FacadeBootstrapListener``` as a listener

###Zend Framework 3
Register ```Mrubiosan\Facade\Framework\Zend3\FacadeBootstrapListener``` as a listener

###On your own
First create an adapter for your service locator. You have three options available:
* ```Mrubiosan\Facade\ServiceLocatorAdapter\ArrayAccessAdapter```: if you're using pimple you can use this.
* ```Mrubiosan\Facade\ServiceLocatorAdapter\CallableAdapter```: you can provide a callable parameter that will receive the service name it should retrieve.
* Implement ```Mrubiosan\Facade\FacadeServiceLocatorInterface```

Afterwards, you just need to make this call
```php
//Aliases are always optional
Mrubiosan\Facade\FacadeLoader::init($yourServiceLocator, ['Alias1' => 'To\Fully\Qualified\Class']);
```