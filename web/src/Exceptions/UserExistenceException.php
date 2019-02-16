<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Throwable;

abstract class UserExistenceException extends \Exception
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 1404, $previous);
    }
}
