<?php declare(strict_types=1);

namespace Pulse\Models\Doctor;

use Pulse\Components\Database;
use Pulse\Definitions;
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
    private $creationDate;
    private $lastLoginDate;

    /**
     * DoctorDetails constructor.
     * @param string $nic
     * @param string $fullName
     * @param string $displayName
     * @param string $category
     * @param string $slmcId
     * @param string $email
     * @param string $phoneNumber
     * @param string|null $creationDate
     * @param string|null $lastLoginDate
     */
    public function __construct(string $nic, string $fullName, string $displayName, string $category, string $slmcId,
                                string $email, string $phoneNumber, ?string $creationDate = null, ?string $lastLoginDate = null)
    {
        $this->nic = $nic;
        $this->fullName = $fullName;
        $this->displayName = $displayName;
        $this->category = $category;
        $this->slmcId = $slmcId;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->creationDate = $creationDate;
        $this->lastLoginDate = $lastLoginDate;
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
            $query['slmc_id'], $query['email'], $query['phone_number'], $query['creation_date'], $query['last_login']);
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

    /**
     * @param string|null $slmcId
     * @param string|null $name
     * @param string|null $category
     * @return array
     */
    public static function searchDoctor(?string $slmcId, ?string $name, ?string $category)
    {
        /// TODO: Implement region search

        $sqlKeys = array();
        if ($name == null) {
            $nameArr = array();
        } else {
            // Split by space
            $nameArr = explode(" ", $name);
        }

        if ($slmcId != null) {
            $slmcSQL = "if(slmc_id LIKE :slmc_id, " . Definitions::SLMC_RELEVANCE_WEIGHT . ", 0)";
            $sqlKeys["slmc_id"] = "%$slmcId%";
        } else {
            $slmcSQL = "0";
        }

        if ($name != null) {
            $nameSQL = array();
            for ($i = 0; $i < sizeof($nameArr); $i++) {
                $key = $nameArr[$i];
                $nameKeyStr = "name_part_$i";
                $nameSQLi = "if(full_name LIKE :$nameKeyStr, " . Definitions::NAME_RELEVANCE_WEIGHT . ", 0)";
                $sqlKeys[$nameKeyStr] = "%$key%";
                array_push($nameSQL, $nameSQLi);
            }
            $nameSQL = implode(" + ", $nameSQL);
        } else {
            $nameSQL = "0";
        }

        if ($category != null) {

            // Only category given
            if ($slmcId == null && $name == null) {
                // 25 doctors with given category
                $query = "SELECT * FROM doctor_details WHERE category = :category";
            } else {
                /**
                 * SELECT *, ( (0) + (if (full_name LIKE '%Saman%', 4, 0) ))  as relevance FROM doctor_details
                 * WHERE category = 'OPD' HAVING relevance > 0 ORDER BY relevance DESC
                 * LIMIT 25
                 */
                $query = "SELECT *, ( ($slmcSQL) + ($nameSQL) )  as relevance
                          FROM doctor_details
                          WHERE category = :category
                          HAVING relevance > 0
                          ORDER BY relevance DESC";
            }
            $sqlKeys["category"] = $category;

        } else {
            if ($slmcId == null && $name == null) {
                // Nothing given
                return array();
            } else {
                /**
                 * SELECT *, ( (0) + (if (full_name LIKE '%Saman%', 4, 0) ))  as relevance
                 * FROM doctor_details HAVING relevance > 0 ORDER BY relevance DESC LIMIT 25
                 */
                $query = "SELECT *, ( ($slmcSQL) + ($nameSQL) )  as relevance
                          FROM doctor_details
                          HAVING relevance > 0
                          ORDER BY relevance DESC";
            }
        }

        $result = Database::query($query, $sqlKeys);
        return $result;
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

    /**
     * @return string|null
     */
    public function getCreationDate(): ?string
    {
        return $this->creationDate;
    }

    /**
     * @param string|null $creationDate
     */
    public function setCreationDate(?string $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return string|null
     */
    public function getLastLoginDate(): ?string
    {
        return $this->lastLoginDate;
    }

    /**
     * @param string|null $lastLoginDate
     */
    public function setLastLoginDate(?string $lastLoginDate): void
    {
        $this->lastLoginDate = $lastLoginDate;
    }
}
