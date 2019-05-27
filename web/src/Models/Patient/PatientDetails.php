<?php

namespace Pulse\Models\Patient;

use Pulse\Components\Database\Database;
use Pulse\Definitions;
use Pulse\Models\Exceptions;
use Pulse\Models\Interfaces\IDetails;

class PatientDetails implements IDetails
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
        $query = Database::queryFirstRow("SELECT * from patient_details WHERE account_id=:account_id",
            array('account_id' => $accountId));

        if ($query == null) {
            throw new Exceptions\AccountNotExistException($accountId);
        }
        return new PatientDetails($query['name'], $query['nic'], $query['email'], $query['phone_number'],
            $query['address'], $query['postal_code']);
    }

    public function saveInDatabase(string $accountId)
    {
        Database::insert('patient_details', array(
            'account_id' => $accountId,
            'name' => $this->getName(),
            'nic' => $this->getNic(),
            'email' => $this->getEmail(),
            'phone_number' => $this->getPhoneNumber(),
            'address' => $this->getAddress(),
            'postal_code' => $this->getPostalCode()
        ));
    }

    public static function searchPatient(?string $name, ?string $nic, ?string $address){
        $sqlKeys = array();

        if ($name == null){
            $nameArr = array();
        }else{
            // Split by space
            $nameArr = explode(" ", $name);
        }

        if ($address == null){
            $addressArr = array();
        }else{
            // Split by space
            $addressArr = explode(" ", $address);
        }

        if ($name != null) {
            $nameSQL = array();
            for($i = 0; $i < sizeof($nameArr); $i++){
                $key = $nameArr[$i];
                $nameKeyStr = "name_part_$i";
                $nameSQLi = "if (name LIKE :$nameKeyStr, ". Definitions::NAME_RELEVANCE_WEIGHT .", 0)";
                $sqlKeys[$nameKeyStr] = "%$key%";
                array_push($nameSQL, $nameSQLi);
            }
            $nameSQL =implode(" + ", $nameSQL);
        }else{
            $nameSQL = "0";
        }

        if ($address != null) {
            $addressSQL = array();
            for($i = 0; $i < sizeof($addressArr); $i++){
                $key = $addressArr[$i];
                $addressKeyStr = "address_part_$i";
                $addressSQLi = "if(address LIKE :$addressKeyStr, ". Definitions::ADDRESS_RELEVANCE_WEIGHT .", 0)";
                $sqlKeys[$addressKeyStr] = "%$key%";
                array_push($addressSQL, $addressSQLi);
            }
            $addressSQL =implode(" + ", $addressSQL);
        }else{
            $addressSQL = "0";
        }

        // NIC Given
        if ($nic != null) {
            $query = "SELECT * FROM patient_details WHERE nic = :nic";
            $result = Database::queryFirstRow($query, array("nic" => $nic));

            if ($result == null) {
                // Not matching NIC - search by other factors
                if ($address == null && $name == null) {
                    // No other given - no results
                    return array();
                }
                // If other factors given - go out of the if statements
            } else {
                // If matched - search no more
                $result['relevance'] = 100;
                return [$result];
            }
        }

        $query = "SELECT *, ( ($addressSQL) + ($nameSQL) )  as relevance
                          FROM patient_details
                          HAVING relevance > 0
                          ORDER BY relevance DESC";
        return Database::query($query, $sqlKeys);
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


