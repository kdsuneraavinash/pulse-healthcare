<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use Pulse\Components\Database;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\Exceptions;

abstract class Account
{
    protected $accountId;
    protected $accountType;

    /**
     * Account constructor.
     * @param string $accountId
     * @param string $accountType
     */
    protected function __construct(string $accountId, string $accountType)
    {
        $this->accountId = $accountId;
        $this->accountType = $accountType;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        $query = Database::queryFirstRow("SELECT account_id from accounts WHERE account_id=:account_id",
            array('account_id' => $this->accountId));

        return $query != null;
    }

    protected function saveInDatabase()
    {
        Database::insert('accounts', array(
            'account_id' => $this->getAccountId(),
            'account_type' => $this->getAccountType()
        ));
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     */
    protected function checkWhetherAccountIDExists()
    {
        $existingAccount = Database::queryFirstRow("SELECT account_id from accounts WHERE account_id=:account_id",
            array('account_id' => $this->accountId));

        if ($existingAccount != null) {
            throw new Exceptions\AccountAlreadyExistsException($existingAccount['account_id']);
        }
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return AccountType
     */
    public function getAccountType(): string
    {
        return $this->accountType;
    }
}

class TempAccount extends Account
{
    public function __construct(string $accountId)
    {
        parent::__construct($accountId, AccountType::Tester);
    }
}