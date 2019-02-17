<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use DB;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\MedicalCenter\MedicalCenterDetails;

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
        $query = DB::queryFirstRow('SELECT * FROM accounts WHERE account_id = %s', $this->accountId);
        return $query != null;
    }

    /**
     * @param string $accountId
     * @return Account|null
     * @throws AccountNotExistException
     */
    public static function retrieveAccount(string $accountId): ?Account
    {
        $account = DB::queryFirstRow("SELECT * FROM accounts WHERE account_id=%s", $accountId);
        if ($account == null) {
            throw new AccountNotExistException($accountId);
        }
        $parsedAccount = null;
        if ($account['account_type'] == 'med_center') {
            $parsedAccount = new MedicalCenter($accountId, MedicalCenterDetails::readFromDatabase($accountId));
        } else if ($account['account_type'] == 'tester') {
            $parsedAccount = new TempAccount($accountId);
        }

        if ($parsedAccount == null) {
            throw new AccountNotExistException($accountId);
        }
        return $parsedAccount;
    }

    protected function saveInDatabase()
    {
        DB::insert('accounts', array(
            'account_id' => $this->accountId,
            'account_type' => $this->accountType
        ));
    }
}

class TempAccount extends Account
{
    public function __construct(string $accountId)
    {
        parent::__construct($accountId, 'tester');
    }
}