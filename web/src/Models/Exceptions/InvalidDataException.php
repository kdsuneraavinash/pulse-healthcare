<?php declare(strict_types=1);

namespace Pulse\Models\Exceptions;

use Throwable;

class InvalidDataException extends \Exception
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 1034, $previous);
    }
}