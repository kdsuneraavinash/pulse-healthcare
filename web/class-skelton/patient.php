<?php

class Patient extends User{
    private $reportList;
    private $prescriptionList;
    private $reminderList;

    public function __construct(){
        $this->reportList = Array();
        $this->prescriptionList = Array();
        $this->reminderList = Array();
    }

    private function viewTimeline(){
        //implemtation of viewTimeline() function
    }

    private function setReminder($reminderDetails){
        //implemtation of setReminder() function
    }

    private function viewMedicineInformation($medicine){
        //implemtation of viewMedicineInformation() function
    }

    private function setReminderNextDate(){
        //implemtation of setReminderNextDate() function
    }

    private function editNotifications(){
        //implemtation of editNotifications() function
    }









}





?>