<?php declare(strict_types=1);

namespace Pulse\Models\Exceptions;

use Throwable;

class PHSRCAlreadyInUse extends AccountExistenceException
{
    public function __construct(string $phsrc, Throwable $previous = null)
    {
        parent::__construct("PHSRC $phsrc is already in use.", $previous);
    }
}