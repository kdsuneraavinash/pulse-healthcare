<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 2/23/19
 * Time: 11:33 AM
 */

namespace Pulse\Models\Patient;

use DB;
use Pulse\Models\Exceptions;

class PatientDetails
{
    private $name;
    private $nic;
    private $email;
    private $phoneNumber;
    private $address;
    private $postalCode;

    /**
     * PatientDetails constructor.
     * @param string $name
     * @param string $nic
     * @param string $email
     * @param string $phoneNumber
     * @param string $address
     * @param string $postalCode
     */
    function __construct(string $name, string $nic, string $email, string $phoneNumber, string $address, string $postalCode)
    {
        $this->name = $name;
        $this->nic = $nic;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->postalCode = $postalCode;
    }

    public function validate()
    {
        $nameValid = $this->name != '';
        $nicValid = $this->nic != '';
        $emailValid = $this->email != "" && preg_match(' /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*' .
                ')|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|' .
                '(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $this->email);
        $phoneNumberValid = $this->phoneNumber != "";
        $addressValid = $this->address != "";
        $postalValid = $this->postalCode != "";
        return $nameValid && $nicValid && $emailValid && $phoneNumberValid && $addressValid && $postalValid;

    }

    /**
     * @param string $accountId
     * @return PatientDetails
     * @throws Exceptions\AccountNotExistException
     */
    public static function readFromDatabase(string $accountId): PatientDetails
    {
        $query = DB::queryFirstRow("SELECT * FROM patient_details WHERE account_id=%s", $accountId);
        if ($query == null) {
            throw new Exceptions\AccountNotExistException($accountId);
        }
        return new PatientDetails($query['name'], $query['nic'], $query['email'], $query['phone_number'],
            $query['address'], $query['postal_code']);
    }


    public function saveInDatabase(string $accountId)
    {
        DB::insert('patient_details', array(
            'account_id' => $accountId,
            'name' => $this->getName(),
            'nic' => $this->getNic(),
            'email' => $this->getEmail(),
            'phone_number' => $this->getPhoneNumber(),
            'address' => $this->getAddress(),
            'postal_code' => $this->getPostalCode()
        ));
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNic()
    {
        return $this->nic;
    }

    /**
     * @param mixed $nic
     */
    public function setNic($nic): void
    {
        $this->nic = $nic;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param $postalCode
     */
    public function setPostalCode($postalCode): void
    {
        $this->postalCode = $postalCode;
    }


}


