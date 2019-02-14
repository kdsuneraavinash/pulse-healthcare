<?php include "account.php"?>
<?php

class MedicalCenter extends Account{
    private $location;
    private $patientsArray;
    private $doctorsArray;

    public function __construct($location){
        $this->location = $location;
        $this->patientsArray = Array();
        $this->doctorsArray = Array();
        

    }

    private function requestRegistration(){
        //implemtation of requestRegistration() function

    }

    private function createPatientAccount(){
        //implemtation of createPatientAccount() function

    }

    private function createDoctorAccount(){
        //implemtation of createDoctorAccount() function

    }

    private function removeUser(){
        //implentation of removeUser() function
    }

  
    private function searchDoctor(){
        //implemtation of searchDoctor() function
    }

    private function searchPatient(){
        //implemtation of searchPatient() function

    }






   
}








?>