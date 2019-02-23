<?php declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

use DB;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Models\Interfaces\IDetails;

class MedicalCenterDetails implements IDetails
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
    public function __construct(string $name, string $phsrc, string $email, string $fax,
                                string $phoneNumber, string $address, string $postalCode)
    {
        $this->name = $name;
        $this->phsrc = $phsrc;
        $this->email = $email;
        $this->fax = $fax;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->postalCode = $postalCode;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $nameValid = $this->name != "";
        $phsrcValid = $this->phsrc != "" && preg_match('/^PHSRC\/[A-Z]+\/[0-9]+$/', $this->phsrc);
        $emailValid = $this->email != "" && preg_match(' /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*' .
                ')|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|' .
                '(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $this->email);
        $faxValid = $this->fax == "" || preg_match('/^\+?[0-9]+$/', $this->fax);
        $phoneNumberValid = $this->phoneNumber != "";
        $addressValid = $this->address != "";
        $postalValid = $this->postalCode != "";
        return $nameValid && $phsrcValid && $emailValid && $faxValid &&
            $phoneNumberValid && $addressValid && $postalValid;
    }

    /**
     * @param string $accountId
     * @return MedicalCenterDetails
     * @throws AccountNotExistException
     */
    public static function readFromDatabase(string $accountId): MedicalCenterDetails
    {
        $query = DB::queryFirstRow("SELECT * FROM medical_center_details WHERE account_id=%s", $accountId);
        if ($query == null) {
            throw new AccountNotExistException($accountId);
        }
        return new MedicalCenterDetails($query['name'], $query['phsrc'], $query['email'], $query['fax'],
            $query['phone_number'], $query['address'], $query['postal_code']);
    }

    /**
     * @param string $accountId
     */
    public function saveInDatabase(string $accountId)
    {
        DB::insert('medical_center_details', array(
            'account_id' => $accountId,
            'name' => $this->getName(),
            'phsrc' => $this->getPhsrc(),
            'email' => $this->getEmail(),
            'fax' => $this->getFax(),
            'phone_number' => $this->getPhoneNumber(),
            'address' => $this->getAddress(),
            'postal_code' => $this->getPostalCode()
        ));
    }


    /*
    --------------------------------------------------------------------------------------------------------------------
    Getters and Setters
    --------------------------------------------------------------------------------------------------------------------
     */

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPhsrc(): string
    {
        return $this->phsrc;
    }

    /**
     * @param string $phsrc
     */
    public function setPhsrc(string $phsrc): void
    {
        $this->phsrc = $phsrc;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFax(): string
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax(string $fax): void
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }
}
