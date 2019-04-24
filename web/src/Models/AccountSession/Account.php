<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use Pulse\Components\Database;
use Pulse\Models\Admin\Admin;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\Exceptions;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\MedicalCenter\MedicalCenterDetails;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Patient\PatientDetails;

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

    /**
     * @param string $accountId
     * @param bool $ignoreMedicalCenterVerificationError
     * @return Account|null
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public static function retrieveAccount(string $accountId, bool $ignoreMedicalCenterVerificationError = false): ?Account
    {
        $account = Database::queryFirstRow("SELECT * from accounts WHERE account_id=:account_id",
            array('account_id' => $accountId));

        if ($account == null) {
            throw new Exceptions\AccountNotExistException($accountId);
        }
        $parsedAccount = null;
        if ($account['account_type'] === (string)AccountType::MedicalCenter) {
            $parsedAccount = new MedicalCenter($accountId, null,
                MedicalCenterDetails::readFromDatabase($accountId), $ignoreMedicalCenterVerificationError);
        } else if ($account['account_type'] === (string)AccountType::Tester) {
            $parsedAccount = new TempAccount($accountId);
        } else if ($account['account_type'] === (string)AccountType::Doctor) {
            $parsedAccount = new Doctor(DoctorDetails::readFromDatabase($accountId));
        } else if ($account['account_type'] === (string)AccountType::Admin) {
            $parsedAccount = new Admin($accountId);
        }else if ($account['account_type'] === (string)AccountType::Patient) {
            $parsedAccount = new Patient(PatientDetails::readFromDatabase($accountId));
        }  else {
            throw new Exceptions\AccountNotExistException($accountId);
        }

        return $parsedAccount;
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