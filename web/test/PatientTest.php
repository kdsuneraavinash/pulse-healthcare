<?php

namespace PulseTest;

use DB;
use PHPUnit\Framework\TestCase;
use Pulse\Components\Database\Database;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Exceptions;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Patient\PatientDetails;


final class PatientTest extends TestCase
{
    private static $nic;
    private static $name;
    private static $email;
    private static $phoneNumber;
    private static $address;
    private static $postalCode;
    private static $unusedNic;
    private static $password;

    private static $patientDetails;

    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {
        LoginService::setTestEnvironment();
        self::$nic = "978978877V";
        self::$name = "Patient Tester";
        self::$email = "tester@medical.patient";
        self::$phoneNumber = "07655667890";
        self::$address = "Fake Number, Fake Street, Fake City, Fake Province.";
        self::$postalCode = "99999";
        self::$unusedNic = "unused_account_id";
        self::$password = "password";

        self::restoreDetails();

        Database::delete('accounts', "account_id = :account_id",
            array('account_id' => self::$nic));
        Database::delete('accounts', "account_id = :account_id",
            array('account_id' => self::$unusedNic));
    }

    /**
     * @afterClass
     */
    public static function deleteSessions()
    {
        LoginService::signOutSession();
    }

    private static function restoreDetails()
    {
        self::$patientDetails = new PatientDetails(
            self::$name,
            self::$nic,
            self::$email,
            self::$phoneNumber,
            self::$address,
            self::$postalCode
        );
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */
    public function testRequestRegistration()
    {
        Patient::register(self::$patientDetails);

        $query = Database::queryFirstRow("SELECT * from patients WHERE account_id=:account_id",
            array('account_id' => self::$nic));
        $this->assertNotNull($query);
        $this->assertNotNull($query['default_password']);

        $query = Database::queryFirstRow("SELECT * from sessions WHERE account_id=:account_id",
            array('account_id' => self::$nic));
        $this->assertNull($query);
    }

    /**
     * @return mixed
     */
    public static function getPatientDetails(): PatientDetails
    {
        return self::$patientDetails;
    }

    /**
     * @depends testRequestRegistration
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfName()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getPatientDetails()->setName("");
        Patient::register(self::$patientDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfEmailEmpty()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getPatientDetails()->setEmail("");
        Patient::register(self::$patientDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfEmailRegex()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getPatientDetails()->setEmail("email.com");
        Patient::register(self::$patientDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfPhoneNumberEmpty()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getPatientDetails()->setPhoneNumber("");
        Patient::register(self::$patientDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfAddressEmpty()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getPatientDetails()->setAddress("");
        Patient::register(self::$patientDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfPostalCodeEmpty()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getPatientDetails()->setPostalCode("");
        Patient::register(self::$patientDetails);
    }
}
