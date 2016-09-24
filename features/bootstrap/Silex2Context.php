<?php
use Mrubiosan\Facade\Framework\Silex2\FacadeProvider;

class Silex2Context extends Silex1Context
{  
    /**
     * @BeforeSuite
     */
    static public function setupAutoloading()
    {
        require __DIR__ . '/../../Test/Silex2/vendor/autoload.php';
    }
    
    protected function getFacadeProvider()
    {
        return new FacadeProvider();
    }
}