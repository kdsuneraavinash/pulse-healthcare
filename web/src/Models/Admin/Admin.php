<?php declare(strict_types=1);

namespace Pulse\Models\Admin;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\Enums\AccountType;

class Admin extends Account
{
    /**
     * MedicalCenter constructor.
     * @param string $accountId
     */
    protected function __construct(string $accountId)
    {
        parent::__construct($accountId, AccountType::Admin());
    }
}
