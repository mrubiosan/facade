<?php

namespace Mrubiosan\Facade\ServiceLocatorAdapter\Exception;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
}
