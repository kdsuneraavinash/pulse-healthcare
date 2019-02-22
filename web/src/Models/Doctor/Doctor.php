<?php declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

use DB;
use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\SLMCAlreadyInUse;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;

class Doctor extends Account
{
    private $doctorDetails;

    /**
     * MedicalCenter constructor.
     * @param string $NIC
     * @param DoctorDetails $doctorDetails
     */
    protected function __construct(string $NIC, DoctorDetails $doctorDetails)
    {
        parent::__construct($NIC, "doctor");
        $this->doctorDetails = $doctorDetails;
    }

    /**
     * @param string $accountId
     * @param DoctorDetails $doctorDetails
     * @param string $password
     * @return Doctor
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     * @throws SLMCAlreadyInUse
     */
    public static function requestRegistration(string $accountId, DoctorDetails $doctorDetails,
                                               string $password): Doctor
    {
        $doctor = new Doctor($accountId, $doctorDetails);
        $doctor->saveInDatabase();
        LoginService::signUpSession($accountId, $password);
        // TODO: Add code to request verification
        return $doctor;
    }

    /**
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws SLMCAlreadyInUse
     */
    protected function saveInDatabase()
    {
        $this->validateFields();

        parent::saveInDatabase();
        DB::insert('doctors', array(
            'account_id' => $this->accountId,
            'verified' => true
        ));
        $this->getDoctorDetails()->saveInDatabase($this->accountId);
    }

    /**
     * @throws AccountAlreadyExistsException
     * @throws SLMCAlreadyInUse
     * @throws InvalidDataException
     */
    private function validateFields()
    {
        $detailsValid = $this->doctorDetails->validate();
        if (!$detailsValid) {
            throw new InvalidDataException("Server side validation failed.");
        }
        $this->checkWhetherAccountIDExists();
        $this->checkWhetherSLMCExists();
    }

    /**
     * @throws AccountAlreadyExistsException
     */
    private function checkWhetherAccountIDExists()
    {
        $existingAccount = DB::queryFirstRow("SELECT account_id from accounts where account_id=%s",
            $this->accountId);
        if ($existingAccount != null) {
            throw new AccountAlreadyExistsException($existingAccount['account_id']);
        }
    }

    /**
     * @throws SLMCAlreadyInUse
     */
    private function checkWhetherSLMCExists()
    {
        $existingDoctor = DB::queryFirstRow("SELECT account_id from doctor_details where slmc_ID=%s",
            $this->doctorDetails->getSlmcID());
        if ($existingDoctor != null) {
            throw new SLMCAlreadyInUse($existingDoctor['account_id']);
        }
    }

    public function getDoctorDetails(): DoctorDetails
    {
        return $this->doctorDetails;
    }
}
