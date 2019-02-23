<?php declare(strict_types=1);

namespace Pulse\Models\Patient;
use Pulse\Models\AccountSession\Account;


class Patient extends Account
{
    private $patientDetails;
    private $reportList;
    private $prescriptionList;
    private $reminderList;

    public function __construct(string $accountId,PatientDetails $patientDetails)
    {
        parent::__construct($accountId,"patient");
        $this->reportList = Array();
        $this->prescriptionList = Array();
        $this->reminderList = Array();
        $this->patientDetails=$patientDetails;
    }

    /**
     * @return PatientDetails
     */
    public function getPatientDetails(): PatientDetails
    {
        return $this->patientDetails;
    }

    /**
     * @param PatientDetails $patientDetails
     */
    public function setPatientDetails(PatientDetails $patientDetails): void
    {
        $this->patientDetails = $patientDetails;
    }

    /**
     * @return array
     */
    public function getReportList(): array
    {
        return $this->reportList;
    }

    /**
     * @param array $reportList
     */
    public function setReportList(array $reportList): void
    {
        $this->reportList = $reportList;
    }

    /**
     * @return array
     */
    public function getPrescriptionList(): array
    {
        return $this->prescriptionList;
    }

    /**
     * @param array $prescriptionList
     */
    public function setPrescriptionList(array $prescriptionList): void
    {
        $this->prescriptionList = $prescriptionList;
    }

    /**
     * @return array
     */
    public function getReminderList(): array
    {
        return $this->reminderList;
    }

    /**
     * @param array $reminderList
     */
    public function setReminderList(array $reminderList): void
    {
        $this->reminderList = $reminderList;
    }



    private function viewTimeline()
    {
        // implementation of viewTimeline() function
    }

    private function setReminder($reminderDetails)
    {
        // implementation of setReminder() function
    }

    private function viewMedicineInformation($medicine)
    {
        // implementation of viewMedicineInformation() function
    }

    private function setReminderNextDate()
    {
        // implementation of setReminderNextDate() function
    }

    private function editNotifications()
    {
        // implementation of editNotifications() function
    }
}