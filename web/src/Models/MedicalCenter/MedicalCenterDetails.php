<?php declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

class MedicalCenterDetails
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPhsrc(): string
    {
        return $this->phsrc;
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
    public function getFax(): string
    {
        return $this->fax;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }


}
