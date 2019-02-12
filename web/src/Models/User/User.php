<?php declare(strict_types=1);

namespace Pulse\Models\User;

use DB;

class User extends BaseUser
{
    public function exists(): bool
    {
        $query = DB::queryFirstRow('SELECT * FROM users WHERE user_id = %s', $this->userId);
        return $query != null;
    }
}