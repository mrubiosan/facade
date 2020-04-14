<?php

namespace Mrubiosan\Facade\Tests\Stub;

use Mrubiosan\Facade\FacadeAccessor;

class FooFacade extends FacadeAccessor
{
    public static function getServiceName()
    {
        return 'foo';
    }
}
