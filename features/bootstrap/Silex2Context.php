<?php
use Mrubiosan\Facade\Framework\Silex2\FacadeProvider;

class Silex2Context extends Silex1Context
{
    protected function getFacadeProvider()
    {
        return new FacadeProvider();
    }
}
