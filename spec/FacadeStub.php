<?php
namespace spec\Mrubiosan\Facade;

use Mrubiosan\Facade\FacadeAccessor;
class FacadeStub extends FacadeAccessor
{
        
     /* (non-PHPdoc)
      * @see \Mrubiosan\Facade\FacadeAccessor::getServiceName()
      */
     static public function getServiceName() {
         return 'dummy';
     }

}