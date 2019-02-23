<?php declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

use DB;
use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\PHSRCAlreadyInUse;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Interfaces\IFavouritable;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Patient\PatientDetails;

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
        parent::__construct($accountId, "med_center");
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
            'account_id' => $this->accountId,
            'verified' => false
        ));
        $this->getMedicalCenterDetails()->saveInDatabase($this->accountId);
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
        $this->checkWhetherAccountIDExists($this->accountId);
        $this->checkWhetherPHSRCExists();
    }

    /**@param string $accountId
     * @throws AccountAlreadyExistsException
     */
    private function checkWhetherAccountIDExists(string $accountId)
    {
        $existingAccount = DB::queryFirstRow("SELECT account_id from accounts where account_id=%s",
            $accountId);
        if ($existingAccount != null) {
            throw new AccountAlreadyExistsException($existingAccount['account_id']);
        }
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



    /**
     * @param Patient $patient
     * @param string $password
     * @throws AccountAlreadyExistsException
     *
     */

    public function createPatientAccount(Patient $patient,string $password)
    {

        $patient->getPatientDetails()->validate();
        $this->checkWhetherAccountIDExists($patient->accountId);
        parent::saveInDatabase();
        DB::insert('patients', array(
            'account_id' => $patient->accountId,
            'name' => $patient->getPatientDetails()->getName()
        ));
        $patient->getPatientDetails()->saveInDatabase($patient->accountId);


    }

    public function createDoctorAccount()
    {
        // TODO: implementation of createDoctorAccount() function
    }

    public function searchDoctor()
    {
        // TODO: implementation of searchDoctor() function
    }

    public function searchPatient()
    {
        // TODO: implementation of searchPatient() function
    }

    public function getMedicalCenterDetails(): MedicalCenterDetails
    {
        return $this->medicalCenterDetails;
    }
}
