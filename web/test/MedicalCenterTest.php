<?php

namespace PulseTest;

use PHPUnit\Framework\TestCase;
use Pulse\Components\Database\Database;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Exceptions;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\MedicalCenter\MedicalCenterDetails;


final class MedicalCenterTest extends TestCase
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

        Database::delete('accounts', "account_id = :account_id",
            array('account_id' => self::$accountId));
        Database::delete('accounts', "account_id = :account_id",
            array('account_id' => self::$unusedAccountId));
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
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testRequestRegistration()
    {
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);


        $query = Database::queryFirstRow("SELECT * from medical_centers WHERE account_id=:account_id",
            array('account_id' => self::$accountId));
        $this->assertNotNull($query);
        $this->assertEquals(0, $query['verified']);

        $query = Database::queryFirstRow("SELECT * from sessions WHERE account_id=:account_id",
            array('account_id' => self::$accountId));
        $this->assertNotNull($query);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testRequestRegistrationWithUsedAccountName()
    {
        $this->expectException(Exceptions\AccountAlreadyExistsException::class);
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
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testRequestRegistrationWithUsedPHSRC()
    {
        $this->expectException(Exceptions\PHSRCAlreadyInUse::class);
        self::restoreDetails();
        MedicalCenter::requestRegistration(self::$unusedAccountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfName()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setName("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfPHSRCEmpty()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setPhsrc("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfPHSRCRegex()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setPhsrc("PHSRC/SD/SD");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfEmailEmpty()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setEmail("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfEmailRegex()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setEmail("email.com");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfFaxEmpty()
    {
        $this->expectException(Exceptions\AccountAlreadyExistsException::class); // No InvalidDataException
        self::restoreDetails();
        self::getMedicalCenterDetails()->setFax("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfFaxRegex()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setFax("FGSAF");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfPhoneNumberEmpty()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setPhoneNumber("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfAddressEmpty()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setAddress("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Models\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Models\Exceptions\AccountNotExistException
     * @throws \Pulse\Models\Exceptions\AccountRejectedException
     * @throws \Pulse\Models\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Models\Exceptions\InvalidDataException
     * @throws \Pulse\Models\Exceptions\PHSRCAlreadyInUse
     */
    public function testDataInvalidationOfPostalCodeEmpty()
    {
        $this->expectException(Exceptions\InvalidDataException::class);
        self::restoreDetails();
        self::getMedicalCenterDetails()->setPostalCode("");
        MedicalCenter::requestRegistration(self::$accountId, self::$medicalCenterDetails,
            self::$password);
    }
}
