<?php declare(strict_types=1);

include "User.php";
include "IPatientAttachable.php";

class Doctor extends User
{
    private $registrationNumber;
    private $medicalCenterList;
    private $loginCredential;
    private $favouriteList;

    public function __construct($loginCredential, $registrationNumber, $firstName, $lastName, $age, $gender, $loginCredentials)
    {
        parent::__construct($firstName, $lastName, $age, $gender, $loginCredentials);
        $this->loginCredential = $loginCredential;
        $this->registrationNumber = $registrationNumber;
        $this->medicalCenterList = Array();
        $this->favouriteList = Array();
    }

    public function checkIn($medicalCenter)
    {
        // implementation of checkIn() function
    }

    public function searchPatient($patientID)
    {
        // implementation of searchPatient() function
    }

    public function addPescription($patientID)
    {
        //i mplementation of addPescription() function
    }

    public function viewPatientTimeline($patientID)
    {
        // implementation of viewPatientTimeline() function
    }

    public function addReport($patientID)
    {
        // implementation of addReport() function
    }

    public function assignNextDate($patientID)
    {
        // implemtation of assignNextDay() function
    }

    public function addBookmark($medicalCenter)
    {
        // implementation of addBookmark() function
    }
}
