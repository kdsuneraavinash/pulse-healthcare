<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Pulse\Utils;
use Throwable;

class PHSRCAlreadyInUse extends AccountExistenceException
{
    public function __construct(string $phsrc, Throwable $previous = null)
    {
        parent::__construct("PHSRC $phsrc is already in use.", $previous);
        Utils::getLogger()->error("PHSRC $phsrc is already in use.");
    }
}