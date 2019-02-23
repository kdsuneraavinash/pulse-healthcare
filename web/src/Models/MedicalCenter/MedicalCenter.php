<?php declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

use DB;
use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\PHSRCAlreadyInUse;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\Interfaces\IFavouritable;

class MedicalCenter extends Account implements IFavouritable
{
    private $medicalCenterDetails;

    /**
     * MedicalCenter constructor.
     * @param string $accountId
     * @param MedicalCenterDetails $medicalCenterDetails
     */
    protected function __construct(string $accountId, MedicalCenterDetails $medicalCenterDetails)
    {
        parent::__construct($accountId, AccountType::MedicalCenter());
        $this->medicalCenterDetails = $medicalCenterDetails;
    }

    /**
     * @param string $accountId
     * @param MedicalCenterDetails $medicalCenterDetails
     * @param string $password
     * @return MedicalCenter
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public static function requestRegistration(string $accountId, MedicalCenterDetails $medicalCenterDetails,
                                               string $password): MedicalCenter
    {
        $medicalCenter = new MedicalCenter($accountId, $medicalCenterDetails);
        $medicalCenter->saveInDatabase();
        LoginService::signUpSession($accountId, $password);
        // TODO: Add code to request verification
        return $medicalCenter;
    }

    /**
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     */
    protected function saveInDatabase()
    {
        $this->validateFields();

        parent::saveInDatabase();
        DB::insert('medical_centers', array(
            'account_id' => parent::getAccountId(),
            'verified' => false
        ));
        $this->getMedicalCenterDetails()->saveInDatabase(parent::getAccountId());
    }

    /**
     * @throws AccountAlreadyExistsException
     * @throws PHSRCAlreadyInUse
     * @throws InvalidDataException
     */
    private function validateFields()
    {
        $detailsValid = $this->medicalCenterDetails->validate();
        if (!$detailsValid) {
            throw new InvalidDataException("Server side validation failed.");
        }
        parent::checkWhetherAccountIDExists();
        $this->checkWhetherPHSRCExists();
    }

    /**
     * @throws PHSRCAlreadyInUse
     */
    private function checkWhetherPHSRCExists()
    {
        $existingMedicalCenter = DB::queryFirstRow("SELECT account_id from medical_center_details where phsrc=%s",
            $this->medicalCenterDetails->getPhsrc());
        if ($existingMedicalCenter != null) {
            throw new PHSRCAlreadyInUse($existingMedicalCenter['account_id']);
        }
    }

    public function createPatientAccount()
    {
        // TODO: implementation of createPatientAccount() function
    }

    /**
     * @param DoctorDetails $doctorDetails
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\SLMCAlreadyInUse
     */
    public function createDoctorAccount(DoctorDetails $doctorDetails)
    {
        Doctor::register($doctorDetails);
    }

    public function searchDoctor()
    {
        // TODO: implementation of searchDoctor() function
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
}
