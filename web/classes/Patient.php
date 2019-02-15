<?php declare(strict_types=1);

class Patient extends User
{
    private $reportList;
    private $prescriptionList;
    private $reminderList;

    public function __construct($firstName, $lastName, $age, $gender, $loginCredentials)
    {
        parent::__construct($firstName, $lastName, $age, $gender, $loginCredentials);
        $this->reportList = Array();
        $this->prescriptionList = Array();
        $this->reminderList = Array();
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