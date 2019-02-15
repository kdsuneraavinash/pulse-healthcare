<?php include "account.php";?>
<?php include "IFavouritable.php";?>

<?php

class MedicalCenter extends Account implements IFavouritable {
    private $location;
    private $patientsArray;
    private $doctorsArray;
    private $loginCredential;

    public function __construct($loginCredential,$location){

        $this->location = $location;
        $this->patientsArray = Array();
        $this->doctorsArray = Array();
        $this->loginCredential = Array();

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