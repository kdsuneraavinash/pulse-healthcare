<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Pulse\StaticLogger;
use Throwable;

class LoginCookiesNotSetException extends \Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("Login Cookies are not enabled", 1405, $previous);
        StaticLogger::loggerError("Login Cookies are not enabled.");
    }
}
