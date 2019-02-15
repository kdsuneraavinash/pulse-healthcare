<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Throwable;

class AlreadyLoggedInException extends \Exception
{
    public function __construct(string $userId, Throwable $previous = null)
    {
        parent::__construct("User is already logged in as $userId.", 1406, $previous);
    }
}
