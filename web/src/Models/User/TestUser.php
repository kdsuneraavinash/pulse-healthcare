<?php declare(strict_types=1);

namespace Pulse\Models\User;

use DB;

class TestUser extends BaseUser
{
    public function exists(): bool
    {
        $query = DB::queryFirstRow('SELECT * FROM test WHERE ID = %s', $this->userId);
        return $query != null;
    }
}