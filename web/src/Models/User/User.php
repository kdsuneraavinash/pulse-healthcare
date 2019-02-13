<?php declare(strict_types=1);

namespace Pulse\Models\User;

use DB;

class User
{
    protected $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
    public function exists(): bool
    {
        $query = DB::queryFirstRow('SELECT * FROM users WHERE user_id = %s', $this->userId);
        return $query != null;
    }
}