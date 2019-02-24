<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use DB;
use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Models\Admin\Admin;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\MedicalCenter\MedicalCenterDetails;

abstract class Account
{
    protected $accountId;
    protected $accountType;

    /**
     * Account constructor.
     * @param string $accountId
     * @param AccountType $accountType
     */
    protected function __construct(string $accountId, AccountType $accountType)
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
     * @param bool $ignoreMedicalCenterVerificationError
     * @return Account|null
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public static function retrieveAccount(string $accountId, bool $ignoreMedicalCenterVerificationError = false): ?Account
    {
        $account = DB::queryFirstRow("SELECT * FROM accounts WHERE account_id=%s", $accountId);
        if ($account == null) {
            throw new AccountNotExistException($accountId);
        }
        $parsedAccount = null;
        if ($account['account_type'] === (string)AccountType::MedicalCenter()) {
            $parsedAccount = new MedicalCenter($accountId, null,
                MedicalCenterDetails::readFromDatabase($accountId), $ignoreMedicalCenterVerificationError);
        } else if ($account['account_type'] === (string)AccountType::Tester()) {
            $parsedAccount = new TempAccount($accountId);
        } else if ($account['account_type'] === (string)AccountType::Doctor()) {
            $parsedAccount = new Doctor(DoctorDetails::readFromDatabase($accountId));
        } else if ($account['account_type'] === (string)AccountType::Admin()) {
            $parsedAccount = new Admin($accountId);
        }else{
            throw new AccountNotExistException($accountId);
        }

        return $parsedAccount;
    }

    protected function saveInDatabase()
    {
        DB::insert('accounts', array(
            'account_id' => $this->getAccountId(),
            'account_type' => (string)$this->getAccountType()
        ));
    }

    /**
     * @throws AccountAlreadyExistsException
     */
    protected function checkWhetherAccountIDExists()
    {
        $existingAccount = DB::queryFirstRow("SELECT account_id from accounts where account_id=%s",
            $this->accountId);
        if ($existingAccount != null) {
            throw new AccountAlreadyExistsException($existingAccount['account_id']);
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
    public function getAccountType(): AccountType
    {
        return $this->accountType;
    }
}

class TempAccount extends Account
{
    public function __construct(string $accountId)
    {
        parent::__construct($accountId, AccountType::Tester());
    }
}