<?php

namespace spec\Mrubiosan\Facade;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mrubiosan\Facade\FacadeServiceLocatorInterface;

class FacadeLoaderSpec extends ObjectBehavior
{
    private $aliases = [
        'FacadeDummyAlias' => 'spec\Mrubiosan\Facade\FacadeStub'
    ];
    
    function let(FacadeServiceLocatorInterface $serviceLocator)
    {
        $this->beConstructedWith($serviceLocator, $this->aliases);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Mrubiosan\Facade\FacadeLoader');
    }
    
    function it_creates_aliases()
    {
        foreach($this->aliases as $alias => $original) {
            $this->shouldAliasClass($alias, $original);
        }
    }
    
    function getMatchers()
    {
        return [
            'aliasClass' => function($subject, $alias, $original) {
                if (class_exists($alias)) {
                    $a = new $alias;
                    $b = new $original;
                    return $a instanceof $b;
                }
                return false;
            }
        ];
    }
}
