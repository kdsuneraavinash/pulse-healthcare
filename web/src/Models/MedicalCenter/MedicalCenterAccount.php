<?php declare(strict_types=1);

namespace Pulse\Models;

use Pulse\Models\Interfaces\IFavouritable;

class MedicalCenter extends Account implements IFavouritable
{
    private $name;
    private $phsrc;
    private $email;
    private $fax;
    private $phoneNumber;
    private $address;
    private $postalCode;


    public function __construct($loginCredential, $location)
    {
        parent::__construct($loginCredential);
        $this->name = $location;
        $this->phsrc = Array();
        $this->email = Array();
        $this->fax = Array();
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
