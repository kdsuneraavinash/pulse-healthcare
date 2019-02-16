<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Throwable;

class UserNotExistException extends UserExistenceException
{
    public function __construct(string $userId, Throwable $previous = null)
    {
        parent::__construct("User $userId does not exist.", $previous);
    }
}