<?php declare(strict_types=1);

namespace Pulse\Models;

use Pulse\Models\AccountSession\Account;
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

    /**
     * MedicalCenter constructor.
     * @param $name
     * @param $phsrc
     * @param $email
     * @param $fax
     * @param $phoneNumber
     * @param $address
     * @param $postalCode
     */
    public function __construct($accountId, $name, $phsrc, $email, $fax, $phoneNumber, $address, $postalCode)
    {
        parent::__construct($accountId);
        $this->name = $name;
        $this->phsrc = $phsrc;
        $this->email = $email;
        $this->fax = $fax;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->postalCode = $postalCode;
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
