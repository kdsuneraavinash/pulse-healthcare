<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Pulse\StaticLogger;
use Throwable;

class SLMCAlreadyInUse extends AccountExistenceException
{
    public function __construct(int $slmc, Throwable $previous = null)
    {
        parent::__construct("SLMC ID $slmc is already in use.", $previous);
        StaticLogger::loggerError("SLMC ID $slmc is already in use.");
    }
}