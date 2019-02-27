<?php declare(strict_types=1);

namespace Pulse\Models\Doctor;

use Pulse\Components\Database;
use Pulse\Models\Exceptions;
use Pulse\Models\Interfaces\IDetails;

class DoctorDetails implements IDetails
{
    private $nic;
    private $fullName;
    private $displayName;
    private $category;
    private $slmcId;
    private $email;
    private $phoneNumber;

    /**
     * DoctorDetails constructor.
     * @param string $nic
     * @param string $fullName
     * @param string $displayName
     * @param string $category
     * @param string $slmcId
     * @param string $email
     * @param string $phoneNumber
     */
    public function __construct(string $nic, string $fullName, string $displayName, string $category, string $slmcId,
                                string $email, string $phoneNumber)
    {
        $this->nic = $nic;
        $this->fullName = $fullName;
        $this->displayName = $displayName;
        $this->category = $category;
        $this->slmcId = $slmcId;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }


    public function validate(): bool
    {
        $nicValid = $this->nic != "";
        $fullNameValid = $this->fullName != "";
        $displayNameValid = $this->displayName != "";
        $categoryValid = $this->category != "";
        $slmcIDValid = $this->slmcId != ""; //TODO: Add real regex matching
        $emailValid = $this->email != "" && preg_match(' /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*' .
                ')|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|' .
                '(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $this->email);
        $phoneNumberValid = $this->phoneNumber != "";
        return $nicValid && $fullNameValid && $displayNameValid && $categoryValid && $slmcIDValid && $emailValid &&
            $phoneNumberValid;
    }

    /**
     * @param string $nic
     * @return DoctorDetails
     * @throws Exceptions\AccountNotExistException
     */
    public static function readFromDatabase(string $nic): DoctorDetails
    {
        $query = Database::queryFirstRow("SELECT * from doctor_details WHERE account_id=:account_id",
            array('account_id' => $nic));

        if ($query == null) {
            throw new Exceptions\AccountNotExistException($nic);
        }
        return new DoctorDetails($nic, $query['full_name'], $query['display_name'], $query['category'],
            $query['slmc_id'], $query['email'], $query['phone_number']);
    }

    public function saveInDatabase(string $accountId)
    {
        Database::insert('doctor_details', array(
            'account_id' => $accountId,
            'nic' => $this->getNic(),
            'full_name' => $this->getFullName(),
            'display_name' => $this->getDisplayName(),
            'category' => $this->getCategory(),
            'slmc_id' => $this->getSlmcId(),
            'email' => $this->getEmail(),
            'phone_number' => $this->getPhoneNumber(),
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
    public function getNic(): string
    {
        return $this->nic;
    }

    /**
     * @param string $nic
     */
    public function setNic(string $nic): void
    {
        $this->nic = $nic;
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
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
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
     * @return mixed
     */
    public function getSlmcId()
    {
        return $this->slmcId;
    }

    /**
     * @param mixed $slmcId
     */
    public function setSlmcId($slmcId): void
    {
        $this->slmcId = $slmcId;
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
