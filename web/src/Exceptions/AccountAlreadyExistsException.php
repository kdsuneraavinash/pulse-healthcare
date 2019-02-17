<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Pulse\Utils;
use Throwable;

class AccountAlreadyExistsException extends AccountExistenceException
{
    public function __construct(string $accountId, Throwable $previous = null)
    {
        parent::__construct("Account $accountId already exists.", $previous);
        Utils::getLogger()->error("Account $accountId already exists.");
    }
}