<?php declare(strict_types=1);

namespace Pulse\Models\Exceptions;

use Throwable;

class AccountAlreadyExistsException extends AccountExistenceException
{
    public function __construct(string $accountId, Throwable $previous = null)
    {
        parent::__construct("Account $accountId already exists.", $previous);
    }
}