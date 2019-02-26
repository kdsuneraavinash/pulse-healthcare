<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Throwable;

class AccountRejectedException extends AccountExistenceException
{
    public function __construct(string $accountId, Throwable $previous = null)
    {
        parent::__construct("Account $accountId was rejected.", $previous);
    }
}