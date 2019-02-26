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
use Pulse\Utils;

class Doctor extends Account implements ICreatable
{
    private $doctorDetails;
    private $defaultPassword;

    /**
     * MedicalCenter constructor.
     * @param DoctorDetails $doctorDetails
     * @param string $defaultPassword
     * @throws InvalidDataException
     */
    protected function __construct(DoctorDetails $doctorDetails, string $defaultPassword = null)
    {
        parent::__construct($doctorDetails->getNic(), AccountType::Doctor);
        $this->doctorDetails = $doctorDetails;
        if ($defaultPassword == null) {
            $query = DB::queryFirstRow('SELECT default_password FROM doctors WHERE account_id = %s', $this->accountId);
            if ($query == null) {
                throw new InvalidDataException("Default password retrieval error.");
            }
            $defaultPassword = $query['default_password'];
        }
        $this->defaultPassword = $defaultPassword;
    }

    /**
     * @param $details
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws SLMCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public static function register($details)
    {
        $password = Utils::generateRandomReadableString(16);
        $doctor = new Doctor($details, $password);
        $doctor->saveInDatabase();
        LoginService::createNewCredentials($doctor->getAccountId(), $password);
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
            'account_id' => parent::getAccountId(),
            'default_password' => $this->getDefaultPassword(),
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
        $detailsValid = $this->getDoctorDetails()->validate();
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

    /**
     * @return DoctorDetails
     */
    public function getDefaultPassword(): string
    {
        return $this->defaultPassword;
    }
}
