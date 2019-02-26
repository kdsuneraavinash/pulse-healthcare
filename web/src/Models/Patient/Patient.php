<?php declare(strict_types=1);

namespace Pulse\Models\Patient;

use DB;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\Exceptions;
use Pulse\Components\Utils;


class Patient extends Account
{
    private $patientDetails;
    private $defaultPassword;
    private $reportList;
    private $prescriptionList;
    private $reminderList;

    /**
     * Patient constructor.
     * @param PatientDetails $patientDetails
     * @param string|null $defaultPassword
     * @throws Exceptions\InvalidDataException
     */
    public function __construct(PatientDetails $patientDetails, string $defaultPassword = null)
    {
        parent::__construct($patientDetails->getNic(), AccountType::Patient);
        if ($defaultPassword == null) {
            $query = DB::queryFirstRow('SELECT default_password FROM doctors WHERE account_id = %s', $this->accountId);
            if ($query == null) {
                throw new Exceptions\InvalidDataException("Default password retrieval error.");
            }
            $defaultPassword = $query['default_password'];
        }
        $this->defaultPassword = $defaultPassword;
        $this->patientDetails = $patientDetails;
        $this->reportList = Array();
        $this->prescriptionList = Array();
        $this->reminderList = Array();
    }

    /**
     * @param $details
     * @return string
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */
    public static function register($details)
    {
        $password = Utils::generateRandomReadableString(16);
        $patient = new Patient($details, $password);
        $patient->saveInDatabase();
        LoginService::createNewCredentials($patient->getAccountId(), $password);
        return $password;
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\InvalidDataException
     */
    protected function saveInDatabase()
    {
        $this->validateFields();
        parent::saveInDatabase();
        DB::insert('patients', array(
            'account_id' => $this->getAccountId(),
            'default_password' => $this->getDefaultPassword()
        ));
        $this->getPatientDetails()->saveInDatabase($this->getAccountId());
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\InvalidDataException
     */
    private function validateFields()
    {
        $detailsValid = $this->getPatientDetails()->validate();
        if (!$detailsValid) {
            throw new Exceptions\InvalidDataException("Server side validation failed.");
        }
        parent::checkWhetherAccountIDExists();
    }

    public function viewTimeline()
    {
        //TODO: implementation of viewTimeline() function
    }

    public function setReminder($reminderDetails)
    {
        //TODO: implementation of setReminder() function
    }

    public function viewMedicineInformation($medicine)
    {
        //TODO: implementation of viewMedicineInformation() function
    }

    public function setReminderNextDate()
    {
        //TODO: implementation of setReminderNextDate() function
    }

    public function editNotifications()
    {
        //TODO: implementation of editNotifications() function
    }

    /*
    --------------------------------------------------------------------------------------------------------------------
    Getters and Setters
    --------------------------------------------------------------------------------------------------------------------
     */

    /**
     * @return PatientDetails
     */
    public function getPatientDetails(): PatientDetails
    {
        return $this->patientDetails;
    }

    /**
     * @return array
     */
    public function getReportList(): array
    {
        return $this->reportList;
    }

    /**
     * @return array
     */
    public function getPrescriptionList(): array
    {
        return $this->prescriptionList;
    }

    /**
     * @return array
     */
    public function getReminderList(): array
    {
        return $this->reminderList;
    }

    /**
     * @return string|null
     */
    public function getDefaultPassword(): ?string
    {
        return $this->defaultPassword;
    }


}