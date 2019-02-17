<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Pulse\StaticLogger;
use Throwable;

class PHSRCAlreadyInUse extends AccountExistenceException
{
    public function __construct(string $phsrc, Throwable $previous = null)
    {
        parent::__construct("PHSRC $phsrc is already in use.", $previous);
        StaticLogger::loggerError("PHSRC $phsrc is already in use.");
    }
}