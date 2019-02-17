<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Pulse\Utils;
use Throwable;

class AccountNotExistException extends AccountExistenceException
{
    public function __construct(string $accountId, Throwable $previous = null)
    {
        parent::__construct("Account $accountId does not exist.", $previous);
        Utils::getLogger()->error("Account $accountId does not exist.");
    }
}