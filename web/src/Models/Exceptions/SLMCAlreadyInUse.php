<?php declare(strict_types=1);

namespace Pulse\Models\Exceptions;

use Throwable;

class SLMCAlreadyInUse extends AccountExistenceException
{
    public function __construct(string $slmc, Throwable $previous = null)
    {
        parent::__construct("SLMC ID $slmc is already in use.", $previous);
    }
}