<?php declare(strict_types=1);

namespace Pulse\Models\Doctor;

use Pulse\Components\Database;
use Pulse\Components\Utils;
use Pulse\Definitions;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\Exceptions;
use Pulse\Models\Interfaces\ICreatable;

class Doctor extends Account implements ICreatable
{
    private $doctorDetails;
    private $defaultPassword;

    /**
     * MedicalCenter constructor.
     * @param DoctorDetails $doctorDetails
     * @param string $defaultPassword
     * @throws Exceptions\InvalidDataException
     */
    protected function __construct(DoctorDetails $doctorDetails, string $defaultPassword = null)
    {
        parent::__construct($doctorDetails->getNic(), AccountType::Doctor);
        $this->doctorDetails = $doctorDetails;
        if ($defaultPassword == null) { // Null means it have to be filled
            // Get default password
            $query = Database::queryFirstRow("SELECT default_password from doctors WHERE account_id=:account_id",
                array('account_id' => $this->accountId));

            if ($query == null) {
                throw new Exceptions\InvalidDataException("Default password retrieval error.");
            }
            $defaultPassword = $query['default_password'];
        }
        $this->defaultPassword = $defaultPassword;
    }

    /**
     * @param $details
     * @return string
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\SLMCAlreadyInUse
     */
    public static function register($details): string
    {
        $password = Utils::generateRandomReadableString(16);
        $doctor = new Doctor($details, $password);
        $doctor->saveInDatabase();
        LoginService::createNewCredentials($doctor->getAccountId(), $password);
        return $password;
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\SLMCAlreadyInUse
     */
    protected function saveInDatabase()
    {
        $this->validateFields();

        parent::saveInDatabase();
        Database::insert('doctors', array(
            'account_id' => parent::getAccountId(),
            'default_password' => $this->getDefaultPassword(),
        ));
        $this->getDoctorDetails()->saveInDatabase(parent::getAccountId());
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\SLMCAlreadyInUse
     */
    private function validateFields()
    {
        $detailsValid = $this->getDoctorDetails()->validate();
        if (!$detailsValid) {
            throw new Exceptions\InvalidDataException("Server side validation failed.");
        }
        parent::checkWhetherAccountIDExists();
        $this->checkWhetherSLMCExists();
    }

    /**
     * @throws Exceptions\SLMCAlreadyInUse
     */
    private function checkWhetherSLMCExists()
    {
        $existingDoctor = Database::queryFirstRow("SELECT account_id from doctor_details WHERE slmc_ID=:slmc_ID",
            array('slmc_ID' => $this->doctorDetails->getSlmcId()));

        if ($existingDoctor != null) {
            throw new Exceptions\SLMCAlreadyInUse($this->doctorDetails->getSlmcId());
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
