<?php declare(strict_types=1);

namespace Pulse\Exceptions;

class UserNotExistException extends UserExistenceException
{
    public function __toString()
    {
        return "Error:: BaseUser '$this->userId' not found";
    }
}