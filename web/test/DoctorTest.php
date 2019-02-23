<?php

namespace PulseTest;

use DB;
use PHPUnit\Framework\TestCase;
use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\SLMCAlreadyInUse;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;

class DoctorTest extends TestCase
{
    private static $nic;
    private static $fullName;
    private static $displayName;
    private static $category;
    private static $slmcId;
    private static $email;
    private static $phoneNumber;
    private static $unusedNic;
    private static $password;

    private static $doctorDetails;

    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {
        \Pulse\Database::init();
        LoginService::setTestEnvironment();
        self::$nic = "652566699V";
        self::$fullName = "Medical Center Tester";
        self::$displayName = "Tester";
        self::$category = "opd";
        self::$slmcId = "1023136";
        self::$email = "tester@doctor.org";
        self::$phoneNumber = "0342225658";
        self::$unusedNic = "000000000V";
        self::$password = "99999";

        self::restoreDetails();

        DB::delete('accounts', "account_id = %s", self::$nic);
        DB::delete('accounts', "account_id = %s", self::$unusedNic);
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
        self::$doctorDetails = new DoctorDetails(
            self::$nic,
            self::$fullName,
            self::$displayName,
            self::$category,
            self::$slmcId,
            self::$email,
            self::$phoneNumber
        );
    }

    /**
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\SLMCAlreadyInUse
     */
    public function testRegister()
    {
        Doctor::register(self::$doctorDetails);
        $query = DB::queryFirstRow("SELECT * FROM doctors WHERE account_id=%s", self::$nic);
        $this->assertNotNull($query);
        $query = DB::queryFirstRow("SELECT * FROM sessions WHERE account_id=%s", self::$nic);
        $this->assertNull($query);
    }

    /**
     * @depends testRegister
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\SLMCAlreadyInUse
     */
    public function testRequestRegistrationWithUsedAccountName()
    {
        $this->expectException(AccountAlreadyExistsException::class);
        self::restoreDetails();
        self::getDoctorDetails()->setSlmcId("0002");
        Doctor::register(self::$doctorDetails);
    }

    /**
     * @return mixed
     */
    public static function getDoctorDetails(): DoctorDetails
    {
        return self::$doctorDetails;
    }

    /**
     * @depends testRegister
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\SLMCAlreadyInUse
     */
    public function testRequestRegistrationWithUsedSLMC()
    {
        $this->expectException(SLMCAlreadyInUse::class);
        self::restoreDetails();
        self::getDoctorDetails()->setNic(self::$unusedNic);
        Doctor::register(self::$doctorDetails);
    }

    /**
     * @depends testRegister
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws SLMCAlreadyInUse
     */
    public function testDataInvalidationOfDisplayName()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getDoctorDetails()->setDisplayName("");
        Doctor::register(self::$doctorDetails);
    }

    /**
     * @depends testRegister
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws SLMCAlreadyInUse
     */
    public function testDataInvalidationOfCategory()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getDoctorDetails()->setCategory("");
        Doctor::register(self::$doctorDetails);
    }

    /**
     * @depends testRegister
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws SLMCAlreadyInUse
     */
    public function testDataInvalidationOfFullName()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getDoctorDetails()->setFullName("");
        Doctor::register(self::$doctorDetails);
    }

    /**
     * @depends testRegister
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws SLMCAlreadyInUse
     */
    public function testDataInvalidationOfPHSRCEmpty()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getDoctorDetails()->setSlmcId("");
        Doctor::register(self::$doctorDetails);
    }

    /**
     * @depends testRegister
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws SLMCAlreadyInUse
     */
    public function testDataInvalidationOfEmailEmpty()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getDoctorDetails()->setEmail("");
        Doctor::register(self::$doctorDetails);
    }

    /**
     * @depends testRegister
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws SLMCAlreadyInUse
     */
    public function testDataInvalidationOfEmailRegex()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getDoctorDetails()->setEmail("email.com");
        Doctor::register(self::$doctorDetails);
    }

    /**
     * @depends testRegister
     * @throws AccountAlreadyExistsException
     * @throws InvalidDataException
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws SLMCAlreadyInUse
     */
    public function testDataInvalidationOfPhoneNumberEmpty()
    {
        $this->expectException(InvalidDataException::class);
        self::restoreDetails();
        self::getDoctorDetails()->setPhoneNumber("");
        Doctor::register(self::$doctorDetails);
    }
}
