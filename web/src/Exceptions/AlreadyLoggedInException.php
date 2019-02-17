<?php declare(strict_types=1);

namespace Pulse\Exceptions;

use Pulse\Utils;
use Throwable;

class AlreadyLoggedInException extends \Exception
{
    public function __construct(string $accountId, Throwable $previous = null)
    {
        parent::__construct("User is already logged in as $accountId.", 1406, $previous);
        Utils::getLogger()->error("User is already logged in as $accountId.");
    }
}
