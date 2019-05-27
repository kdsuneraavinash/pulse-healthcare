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

class AccountFactory
{

    /**
     * @param string $accountId
     * @param bool $ignoreMedicalCenterVerificationError
     * @return Account|null
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function getAccount(string $accountId, bool $ignoreMedicalCenterVerificationError = false): ?Account
    {
        $account = Database::queryFirstRow("SELECT * from accounts WHERE account_id=:account_id",
            array('account_id' => $accountId));

        if ($account == null) {
            throw new Exceptions\AccountNotExistException($accountId);
        }
        $parsedAccount = null;
        if ($account['account_type'] === (string)AccountType::MedicalCenter) {
            $parsedAccount = new MedicalCenter($accountId, null, MedicalCenterDetails::readFromDatabase($accountId), $ignoreMedicalCenterVerificationError);
        } else if ($account['account_type'] === (string)AccountType::Tester) {
            $parsedAccount = new TempAccount($accountId);
        } else if ($account['account_type'] === (string)AccountType::Doctor) {
            $parsedAccount = new Doctor(DoctorDetails::readFromDatabase($accountId));
        } else if ($account['account_type'] === (string)AccountType::Admin) {
            $parsedAccount = new Admin($accountId);
        } else if ($account['account_type'] === (string)AccountType::Patient) {
            $parsedAccount = new Patient(PatientDetails::readFromDatabase($accountId));
        } else {
            throw new Exceptions\AccountNotExistException($accountId);
        }

        return $parsedAccount;
    }
}