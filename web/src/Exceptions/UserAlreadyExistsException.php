<?php declare(strict_types=1);

namespace Pulse\Exceptions;

class UserAlreadyExistsException extends UserExistenceException
{
    public function __toString()
    {
        return "Error:: BaseUser '$this->userId' already exists";
    }
}