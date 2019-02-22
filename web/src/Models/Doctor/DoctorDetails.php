<?php declare(strict_types=1);

namespace Pulse\Models\Doctor;

use DB;
use Pulse\Exceptions\AccountNotExistException;

class DoctorDetails
{
    private $fullName;
    private $displayName;
    private $category;
    private $slmcId;
    private $email;
    private $phoneNumber;

    /**
     * DoctorDetails constructor.
     * @param $fullName
     * @param $displayName
     * @param $category
     * @param $slmcId
     * @param $email
     * @param $phoneNumber
     */
    public function __construct($fullName, $displayName, $category, $slmcId, $email, $phoneNumber)
    {
        $this->fullName = $fullName;
        $this->displayName = $displayName;
        $this->category = $category;
        $this->$slmcId = $slmcId;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }


    public function validate()
    {
        $fullNameValid = $this->fullName != "";
        $displayNameValid = $this->displayName != "";
        $categoryValid = $this->category != "";
        $slmcIDValid = $this->slmcId != "" && preg_match('/[0-9]/', $this->slmcId); //TODO: Add real regex matching
        $emailValid = $this->email != "" && preg_match(' /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*' .
                ')|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|' .
                '(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $this->email);
        $phoneNumberValid = $this->phoneNumber != "";
        return $fullNameValid && $displayNameValid && $categoryValid && $slmcIDValid && $emailValid &&
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
        return new DoctorDetails($query['full_name'],$query['name'],$query['category'], $query['slmc_id'], $query['email'],
            $query['phone_number']);
    }

    public function saveInDatabase(string $accountId)
    {
        DB::insert('doctor_details', array(
            'account_id' => $accountId,
            'full_name'=>$this->getFullName(),
            'name' => $this->getDisplayName(),
            'category'=>$this->getCategory(),
            'slmc_id' => $this->getSlmcId(),
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
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getSlmcId(): string
    {
        return $this->slmcId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }


    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }


}
