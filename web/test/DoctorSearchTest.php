<?php

namespace PulseTest;

use PHPUnit\Framework\TestCase;
use Pulse\Components\Database;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\Models\Exceptions;


final class DoctorSearchTest extends TestCase
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
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\SLMCAlreadyInUse
     */
    public static function setSharedVariables()
    {
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

        Database::delete('accounts', "account_id = :account_id",
            array('account_id' => self::$nic));
        Database::delete('accounts', "account_id = :account_id",
            array('account_id' => self::$unusedNic));
        Doctor::register(self::$doctorDetails);
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

    public function testSearchByCategory()
    {
        $result = DoctorDetails::searchDoctor( null, null, "opd");
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( null, null, "opd-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
    }

    public function testSearchBySLMC()
    {
        $result = DoctorDetails::searchDoctor(self::$slmcId, null, null);
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( "fake-gibberish-slmc", null, null);
        $this->assertEquals(sizeof($result), 0);
    }

    public function testSearchByName()
    {
        $result = DoctorDetails::searchDoctor(null, "Center", null);
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( null, "fake-gibberish-name", null);
        $this->assertEquals(sizeof($result), 0);
    }

    public function testSearchByNameAndCategory()
    {
        $result = DoctorDetails::searchDoctor(null, "Center", "opd");
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor(null, "fake-gibberish-name", "opd");
        $this->assertEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor(null, "Center", "opd-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( null, "fake-gibberish-name", "opd-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
    }

    public function testSearchBySLMCAndCategory()
    {
        $result = DoctorDetails::searchDoctor(self::$slmcId, null, "opd");
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( "fake-gibberish-slmc", null, "opd");
        $this->assertEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor(self::$slmcId, null, "opd-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( "fake-gibberish-slmc", null, "opd-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
    }

    /*
     * Here all are given to a relevance, so no matches iff both fields don't match
     */
    public function testSearchBySLMCAndName()
    {
        $result = DoctorDetails::searchDoctor(self::$slmcId, "Center", null);
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( "fake-gibberish-slmc", "Center", null);
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor(self::$slmcId, "fake-gibberish-name", null);
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( "fake-gibberish-slmc", "fake-gibberish-name", null);
        $this->assertEquals(sizeof($result), 0);
    }

    /*
     * If Category wrong - no matches
     * If category matches - search by relevence - so no results iff both fields wrong
     */
    public function testSearchBySLMCAndNameAndCategory()
    {
        $result = DoctorDetails::searchDoctor(self::$slmcId, "Center", "opd");
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor(self::$slmcId, "fake-gibberish-name", "opd");
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor(self::$slmcId, "Center", "opd-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( self::$slmcId, "fake-gibberish-name", "opd-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor("fake-gibberish-slmc", "Center", "opd");
        $this->assertNotEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor("fake-gibberish-slmc", "fake-gibberish-name", "opd");
        $this->assertEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor("fake-gibberish-slmc", "Center", "opd-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
        $result = DoctorDetails::searchDoctor( "fake-gibberish-slmc", "fake-gibberish-name", "opd-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
    }
}
