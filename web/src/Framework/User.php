<?php declare(strict_types=1);

namespace Pulse\Framework;

use DB;

class User
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function getUserIfExists($userId): ?array
    {
        $query = DB::queryFirstRow('SELECT * FROM test WHERE ID = %s', $userId);
        return $query;
    }

    public function getID(): ?string
    {
        return $this->id;
    }
}