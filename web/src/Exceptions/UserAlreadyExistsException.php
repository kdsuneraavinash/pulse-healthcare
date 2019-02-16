<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Throwable;

class UserAlreadyExistsException extends UserExistenceException
{
    public function __construct(string $userId, Throwable $previous = null)
    {
        parent::__construct("User $userId already exists.", $previous);
    }
}