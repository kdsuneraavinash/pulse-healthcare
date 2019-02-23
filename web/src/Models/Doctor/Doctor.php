<?php declare(strict_types=1);

namespace Pulse\Models\Doctor;

use DB;
use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\SLMCAlreadyInUse;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\Interfaces\ICreatable;
use Pulse\StaticLogger;
use Pulse\Utils;

class Doctor extends Account implements ICreatable
{
    private $doctorDetails;

    /**
     * MedicalCenter constructor.
     * @param DoctorDetails $doctorDetails
     */
    protected function __construct(DoctorDetails $doctorDetails)
    {
        parent::__construct($doctorDetails->getNic(), AccountType::Doctor());
        $this->doctorDetails = $doctorDetails;
    }

    /**
     * @param $details
     * @return string
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws SLMCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public static function register($details): string
    {
        $password = Utils::generateRandomString(8);
        //TODO: Show password to medical center
        StaticLogger::loggerInfo("$password");
        $doctor = new Doctor($details);
        $doctor->saveInDatabase();
        LoginService::createNewCredentials($doctor->getAccountId(), $password);
        return $password;
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
            'account_id' => parent::getAccountId()
        ));
        $this->getDoctorDetails()->saveInDatabase(parent::getAccountId());
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
        parent::checkWhetherAccountIDExists();
        $this->checkWhetherSLMCExists();
    }

    /**
     * @throws SLMCAlreadyInUse
     */
    private function checkWhetherSLMCExists()
    {
        $existingDoctor = DB::queryFirstRow("SELECT account_id from doctor_details where slmc_ID=%s",
            $this->doctorDetails->getSlmcId());
        if ($existingDoctor != null) {
            throw new SLMCAlreadyInUse($this->doctorDetails->getSlmcId());
        }
    }

    /**
     * @return DoctorDetails
     */
    public function getDoctorDetails(): DoctorDetails
    {
        return $this->doctorDetails;
    }
}
