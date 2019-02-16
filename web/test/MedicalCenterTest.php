<?php

namespace PulseTest;

use DB;
use PHPUnit\Framework\TestCase;
use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\PHSRCAlreadyInUse;
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

    private static $medicalCenterDetails;

    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {
        \Pulse\Database::init();
        MedicalCenterTest::$accountId = "medical_center_tester";
        MedicalCenterTest::$name = "Medical Center Tester";
        MedicalCenterTest::$phsrc = "PHSRC/TEST/001";
        MedicalCenterTest::$email = "tester@medical.center";
        MedicalCenterTest::$fax = "0102313546";
        MedicalCenterTest::$phoneNumber = "07655667890";
        MedicalCenterTest::$address = "Fake Number, Fake Street, Fake City, Fake Province.";
        MedicalCenterTest::$postalCode = "99999";
        MedicalCenterTest::$unusedAccountId = "unused_account_id";

        MedicalCenterTest::restoreDetails();

        DB::delete('accounts', "account_id = %s", MedicalCenterTest::$accountId);
        DB::delete('accounts', "account_id = %s", MedicalCenterTest::$unusedAccountId);
    }

    private static function restoreDetails(){
        MedicalCenterTest::$medicalCenterDetails = new MedicalCenterDetails(
            MedicalCenterTest::$name,
            MedicalCenterTest::$phsrc,
            MedicalCenterTest::$email,
            MedicalCenterTest::$fax,
            MedicalCenterTest::$phoneNumber,
            MedicalCenterTest::$address,
            MedicalCenterTest::$postalCode
        );
    }

    /**
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testRequestRegistration()
    {
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
        $query = DB::query("SELECT account_id FROM medical_centers WHERE account_id=%s", MedicalCenterTest::$accountId);
        $this->assertNotNull($query);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testRequestRegistrationWithUsedAccountName()
    {
        $this->expectException(AccountAlreadyExistsException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setPhsrc("PHSRC/UNUSED/002");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
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
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testRequestRegistrationWithUsedPHSRC()
    {
        $this->expectException(PHSRCAlreadyInUse::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenter::requestRegistration(MedicalCenterTest::$unusedAccountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfName()
    {
        $this->expectException(InvalidDataException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setName("");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfPHSRCEmpty()
    {
        $this->expectException(InvalidDataException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setPhsrc("");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfPHSRCRegex()
    {
        $this->expectException(InvalidDataException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setPhsrc("PHSRC/SD/SD");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfEmailEmpty()
    {
        $this->expectException(InvalidDataException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setEmail("");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfEmailRegex()
    {
        $this->expectException(InvalidDataException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setEmail("email.com");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfFaxEmpty()
    {
        $this->expectException(AccountAlreadyExistsException::class); // No InvalidDataException
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setFax("");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfFaxRegex()
    {
        $this->expectException(InvalidDataException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setFax("FGSAF");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfPhoneNumberEmpty()
    {
        $this->expectException(InvalidDataException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setPhoneNumber("");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfAddressEmpty()
    {
        $this->expectException(InvalidDataException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setAddress("");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }

    /**
     * @depends testRequestRegistration
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testDataInvalidationOfPostalCodeEmpty()
    {
        $this->expectException(InvalidDataException::class);
        MedicalCenterTest::restoreDetails();
        MedicalCenterTest::getMedicalCenterDetails()->setPostalCode("");
        MedicalCenter::requestRegistration(MedicalCenterTest::$accountId, MedicalCenterTest::$medicalCenterDetails);
    }
}
