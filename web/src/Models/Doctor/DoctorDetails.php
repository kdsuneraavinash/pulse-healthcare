<?php declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

use DB;
use Pulse\Exceptions\AccountNotExistException;

class DoctorDetails
{
    private $fullName;
    private $name;
    private $category;
    private $slmcID;
    private $email;
    private $phoneNumber;

    /**
     * DoctorDetails constructor.
     * @param $fullName
     * @param $name
     * @param $category
     * @param $slmcID
     * @param $email
     * @param $phoneNumber
     */
    public function __construct($fullName, $name, $category, $slmcID, $email, $phoneNumber)
    {
        $this->fullName = $fullName;
        $this->name = $name;
        $this->category = $category;
        $this->slmcID = $slmcID;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }


    public function validate()
    {
        $fullNameValid = $this->fullName != "";
        $nameValid = $this->name != "";
        $categoryValid = $this->category != "";
        $slmcIDValid = $this->slmcID != "" && preg_match('/[0-9]/', $this->slmcID);
        $emailValid = $this->email != "" && preg_match(' /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*' .
                ')|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|' .
                '(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $this->email);
        $phoneNumberValid = $this->phoneNumber != "";
        return $fullNameValid && $nameValid && $categoryValid && $slmcIDValid && $emailValid &&
            $phoneNumberValid;
    }

    /**
     * @param string $accountId
     * @return DoctorDetails
     * @throws AccountNotExistException
     */
    public static function readFromDatabase(string $accountId) : DoctorDetails
    {
        $query = DB::queryFirstRow("SELECT * FROM doctor_details WHERE account_id=%s", $accountId);
        if ($query == null) {
            throw new AccountNotExistException($accountId);
        }
        return new DoctorDetails($query['full_name'],$query['name'],$query['category'], $query['slmc_ID'], $query['email'],
            $query['phone_number']);
    }

    public function saveInDatabase(string $accountId)
    {
        DB::insert('doctor_details', array(
            'account_id' => $accountId,
            'full_name'=>$this->getFullName(),
            'name' => $this->getName(),
            'category'=>$this->getCategory(),
            'slmc_ID' => $this->getSlmcID(),
            'email' => $this->getEmail(),
            'phone_number' => $this->getPhoneNumber(),
        ));
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

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
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getSlmcID(): string
    {
        return $this->slmcID;
    }

    /**
     * @param string $slmcID
     */
    public function setSlmcID(string $slmcID): void
    {
        $this->slmcID = $slmcID;
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



}
