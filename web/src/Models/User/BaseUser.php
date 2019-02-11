<?php declare(strict_types=1);

namespace Pulse\Models\User;

use Pulse\Models\BaseModel;

abstract class BaseUser implements BaseModel
{
    protected $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public abstract function exists(): bool;
}