<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Pulse\StaticLogger;
use Throwable;

class AccountAlreadyExistsException extends AccountExistenceException
{
    public function __construct(string $accountId, Throwable $previous = null)
    {
        parent::__construct("Account $accountId already exists.", $previous);
        StaticLogger::loggerError("Account $accountId already exists.");
    }
}