<?php declare(strict_types=1);

namespace Pulse\Models\Exceptions;

use Throwable;

class NoPrescriptionsException extends \Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("No prescriptions to view.", 1408, $previous);
    }
}