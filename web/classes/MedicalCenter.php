<?php declare(strict_types=1);

include "Account.php";
include "IFavouritable.php";

class MedicalCenter extends Account implements IFavouritable
{
    private $location;
    private $patientsArray;
    private $doctorsArray;
    private $loginCredential;

    public function __construct($loginCredential, $location)
    {
        parent::__construct($loginCredential);
        $this->location = $location;
        $this->patientsArray = Array();
        $this->doctorsArray = Array();
        $this->loginCredential = Array();
    }

    public function requestRegistration()
    {
        // implementation of requestRegistration() function
    }

    public function createPatientAccount()
    {
        // implementation of createPatientAccount() function
    }

    public function createDoctorAccount()
    {
        // implementation of createDoctorAccount() function
    }

    public function removeUser()
    {
        // implementation of removeUser() function
    }


    public function searchDoctor()
    {
        // implementation of searchDoctor() function
    }

    public function searchPatient()
    {
        // implementation of searchPatient() function

    }
}
