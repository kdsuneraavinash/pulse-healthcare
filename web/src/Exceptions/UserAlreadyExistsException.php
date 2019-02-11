<?php declare(strict_types=1);

namespace Pulse\Exceptions;

class UserAlreadyExistsException extends UserExistenceException
{
    public function __toString()
    {
        return "Error:: User '$this->userId' already exists";
    }
}