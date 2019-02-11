<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Throwable;

abstract class UserExistenceException extends \Exception
{
    protected $userId;

    public function __construct(string $userId, Throwable $previous = null)
    {
        $this->userId = $userId;
        parent::__construct("Error:: User '$this->userId' not found", 1404, $previous);
    }
}
