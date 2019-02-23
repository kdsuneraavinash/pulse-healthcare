<?php

namespace PulseTest;

use DB;
use PHPUnit\Framework\TestCase;
use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\PHSRCAlreadyInUse;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\MedicalCenter\MedicalCenterDetails;

class MedicalCenterTest extends TestCase
{
    private static $accountId;
    private static $name;
    private static $phsrc;
    private static $email;
    private static $fax;
    private static $phoneNumber;
    private static $address;
    private static $postalCode;
    private static $unusedAccountId;
    private static $password;

    private static $medicalCenterDetails;

    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {
        \Pulse\Database::init();
        LoginService::setTestEnvironment();
        self::$accountId = "medical_center_tester";
        self::$name = "Medical Center Tester";
        self::$phsrc = "PHSRC/TEST/001";
        self::$email = "tester@medical.center";
        self::$fax = "0102313546";
        self::$phoneNumber = "07655667890";
        self::$address = "Fake Number, Fake Street, Fake City, Fake Province.";
        self::$postalCode = "99999";
        self::$unusedAccountId = "unused_account_id";
        self::$password = "password";

        self::restoreDetails();

        DB::delete('accounts', "account_id = %s", self::$accountId);
        DB::delete('accounts', "account_id = %s", self::$unusedAccountId);
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
        self::$medicalCenterDetails = new MedicalCenterDetails(
            self::$name,
            self::$phsrc,
            self::$email,
            self::$fax,
            self::$phoneNumber,
            self::$address,
            self::$postalCode
        );
    }

    /**
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testRequestRegistration()
    {
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
        $query = DB::queryFirstRow("SELECT * FROM medical_centers WHERE account_id=%s", self::$accountId);
        $this->assertNotNull($query);
        $this->assertEquals(0, $query['verified']);
        $query = DB::queryFirstRow("SELECT * FROM sessions WHERE account_id=%s", self::$accountId);
        $this->assertNotNull($query);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testRequestRegistrationWithUsedAccountName()
    {
        $this->expectException(AccountAlreadyExistsException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setPhsrc("PHSRC/UNUSED/002");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @return mixed
     */
    public static function getMedicalCenterDetails(): MedicalCenterDetails
    {
        return self::$medicalCenterDetails;
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testRequestRegistrationWithUsedPHSRC()
    {
        $this->expectException(PHSRCAlreadyInUse::class);
        self::restoreDetails();
        MedicalCenter::requestRegistration(self::$unusedAccountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfName()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setName("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfPHSRCEmpty()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setPhsrc("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfPHSRCRegex()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setPhsrc("PHSRC/SD/SD");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfEmailEmpty()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setEmail("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfEmailRegex()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setEmail("email.com");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfFaxEmpty()
    {
        $this->expectException(AccountAlreadyExistsException::class); // No InvalidDataException
        self::restoreDetails();
        self::getMedicalCenterDetails()->setFax("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfFaxRegex()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setFax("FGSAF");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfPhoneNumberEmpty()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setPhoneNumber("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfAddressEmpty()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setAddress("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testDataInvalidationOfPostalCodeEmpty()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setPostalCode("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }
}
