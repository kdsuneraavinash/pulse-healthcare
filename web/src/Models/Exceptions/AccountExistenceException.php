<?php declare(strict_types=1);

namespace Pulse\Models\Exceptions;

use Throwable;

abstract class AccountExistenceException extends \Exception
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 1404, $previous);
    }
}
