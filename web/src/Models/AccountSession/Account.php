<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use DB;

abstract class Account
{
    protected $accountId;

    protected function __construct(string $accountId)
    {
        $this->accountId = $accountId;
    }

    public function exists(): bool
    {
        $query = DB::queryFirstRow('SELECT * FROM accounts WHERE account_id = %s', $this->accountId);
        return $query != null;
    }

    public static function retrieveAccount(string $accountId): Account
    {
        return new TempAccount($accountId);
    }
}

class TempAccount extends Account
{
    public function __construct(string $accountId)
    {
        parent::__construct($accountId);
    }
}