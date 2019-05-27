<?php declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

use Pulse\Components\Database;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\Enums\VerificationState;
use Pulse\Models\Exceptions;
use Pulse\Models\Interfaces\IFavouritable;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Patient\PatientDetails;

class MedicalCenter extends Account implements IFavouritable
{
    private $medicalCenterDetails;
    private $verificationState;

    /**
     * MedicalCenter constructor.
     * @param string $accountId
     * @param AbstractVerificationState|null $verificationState
     * @param MedicalCenterDetails $medicalCenterDetails
     * @param bool $ignoreErrors
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     */
    function __construct(string $accountId, ? AbstractVerificationState $verificationState,
                                   MedicalCenterDetails $medicalCenterDetails, bool $ignoreErrors = false)
    {
        parent::__construct($accountId, AccountType::MedicalCenter);
        $this->medicalCenterDetails = $medicalCenterDetails;
        if ($verificationState === null) {
            // Need to fetch from database
            $query = Database::queryFirstRow("SELECT verified from medical_centers WHERE account_id=:account_id",
                array('account_id' => $accountId));

            if ($query == null) {
                throw new Exceptions\AccountNotExistException($accountId);
            }


            if ((int)$query['verified'] == 0) {
                $this->verificationState = new UnverifiedState();
            } elseif ((int)$query['verified'] == 1) {
                $this->verificationState = new VerifiedState();
            } else {
                $this->verificationState = new RejectedState();
            }

            if (!$ignoreErrors && $this->getVerificationState() == VerificationState::Rejected) {
                throw new Exceptions\AccountRejectedException($accountId);
            }
        } else {
            $this->verificationState = $verificationState;
        }
    }

    /**
     * @param string $accountId
     * @param MedicalCenterDetails $medicalCenterDetails
     * @param string $password
     * @return MedicalCenter
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\AlreadyLoggedInException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\PHSRCAlreadyInUse
     */
    public static function requestRegistration(string $accountId, MedicalCenterDetails $medicalCenterDetails,
                                               string $password): MedicalCenter
    {
        $medicalCenter = new MedicalCenter($accountId, new UnverifiedState(), $medicalCenterDetails);
        $medicalCenter->saveInDatabase();
        LoginService::signUpSession($accountId, $password);
        // TODO: Add code to request verification
        return $medicalCenter;
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\PHSRCAlreadyInUse
     */
    protected function saveInDatabase()
    {
        $this->validateFields();

        parent::saveInDatabase();
        Database::insert('medical_centers', array(
            'account_id' => parent::getAccountId(),
            'verified' => (string)$this->getVerificationState()
        ));
        $this->getMedicalCenterDetails()->saveInDatabase(parent::getAccountId());
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\PHSRCAlreadyInUse
     */
    private function validateFields()
    {
        $detailsValid = $this->medicalCenterDetails->validate();
        if (!$detailsValid) {
            throw new Exceptions\InvalidDataException("Server side validation failed.");
        }
        parent::checkWhetherAccountIDExists();
        $this->checkWhetherPHSRCExists();
    }

    /**
     * @throws Exceptions\PHSRCAlreadyInUse
     */
    private function checkWhetherPHSRCExists()
    {
        $existingMedicalCenter = Database::queryFirstRow("SELECT account_id from medical_center_details WHERE phsrc=:phsrc",
            array('phsrc' => $this->medicalCenterDetails->getPhsrc()));

        if ($existingMedicalCenter != null) {
            throw new Exceptions\PHSRCAlreadyInUse($existingMedicalCenter['account_id']);
        }
    }

    /**
     * @param PatientDetails $patientDetails
     * @return string
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */

    public function createPatientAccount(PatientDetails $patientDetails): string
    {
        return Patient::register($patientDetails);
    }

    /**
     * @param DoctorDetails $doctorDetails
     * @return string
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\SLMCAlreadyInUse
     */
    public function createDoctorAccount(DoctorDetails $doctorDetails): string
    {
        return Doctor::register($doctorDetails);
    }

    public function searchPatient()
    {
        // TODO: implementation of searchPatient() function
    }

    /**
     * @return MedicalCenterDetails
     */
    public function getMedicalCenterDetails(): MedicalCenterDetails
    {
        return $this->medicalCenterDetails;
    }

    /**
     * @return int
     */
    public function getVerificationState(): int
    {
        return $this->verificationState->getStatus();
    }


}
