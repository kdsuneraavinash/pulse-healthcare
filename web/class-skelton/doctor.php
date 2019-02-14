<?php include "user.php";?>
<?php include "IPatientAttachable.php";?>
<?php

class Doctor extends User {
    private $registrationNumber;
    private $medicalCenterList;
    private $loginCredential;
    private $favouriteList;

    public function __construct($loginCredential,$registrationNumber){
        $this->loginCredential=$loginCredential;
        $this->registrationNumber=$registrationNumber;
        $this->medicalCenterList = Array();
        $this->favouriteList = Array();

    }

    private function checkIn($medicalCenter){
        //implementation of checkIn() function
    }

    private function searchPatient($patientID){
        //implementation of searchPatient() function
    }

    private function addPescription($patientID){
        //implementation of addPescription() function
    }

    private function viewPatientTimeline($patientID){
        //implementation of viewPatientTimeline() function
    }

    private function addReport($patientID){
        //implementation of addReport() function
    }

    private function assignNextDate($patientID){
        //implemtation of assignNextDay() function
    }

    private function addBookmark($medicalCenter){
        //implementation of addBookmark() function
    }



}






?>