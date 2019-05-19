<?php

namespace PulseTest;

use PHPUnit\Framework\TestCase;
use Pulse\Components\Database;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Patient\PatientDetails;
use Pulse\Models\Exceptions;
use Pulse\Models\Patient\Patient;


final class PatientSearchTest extends TestCase
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
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
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
        Patient::register(self::$patientDetails);
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

    public function testSearchByAddress()
    {
        $result = PatientDetails::searchPatient( null, null, "City");
        $this->assertNotEquals(sizeof($result), 0);
        $result = PatientDetails::searchPatient( null, null, "address-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
    }

    public function testSearchByName()
    {
        $result = PatientDetails::searchPatient("Patient", null, null);
        $this->assertNotEquals(sizeof($result), 0);
        $result = PatientDetails::searchPatient( "fake-gibberish-name", null, null);
        $this->assertEquals(sizeof($result), 0);
    }

    // If NIC given only one match will be shown
    public function testSearchByNIC()
    {
        $result = PatientDetails::searchPatient(null, self::$nic, null);
        $this->assertEquals(sizeof($result), 1);
        $result = PatientDetails::searchPatient( null, "fake-gibberish-nic", null);
        $this->assertEquals(sizeof($result), 0);
    }

    public function testSearchByNameAndAddress()
    {
        $result = PatientDetails::searchPatient( "Patient", null, "City");
        $this->assertNotEquals(sizeof($result), 0);
        $result = PatientDetails::searchPatient( "Patient", null, "address-gibberish-fake");
        $this->assertNotEquals(sizeof($result), 0);
        $result = PatientDetails::searchPatient( "fake-gibberish-name", null, "City");
        $this->assertNotEquals(sizeof($result), 0);
        $result = PatientDetails::searchPatient( "fake-gibberish-name", null, "address-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
    }

    public function testSearchByNameAndNIC()
    {
        $result = PatientDetails::searchPatient("Patient", self::$nic, null);
        $this->assertEquals(sizeof($result), 1);
        $result = PatientDetails::searchPatient( "Patient", "fake-gibberish-nic", null);
        $this->assertNotEquals(sizeof($result), 0);
        $result = PatientDetails::searchPatient("fake-gibberish-name", self::$nic, null);
        $this->assertEquals(sizeof($result), 1);
        $result = PatientDetails::searchPatient( "fake-gibberish-name", "fake-gibberish-nic", null);
        $this->assertEquals(sizeof($result), 0);
    }

    public function testSearchByAddressAndNIC()
    {
        $result = PatientDetails::searchPatient(null, self::$nic, "City");
        $this->assertEquals(sizeof($result), 1);
        $result = PatientDetails::searchPatient( null, "fake-gibberish-nic", "City");
        $this->assertNotEquals(sizeof($result), 0);
        $result = PatientDetails::searchPatient(null, self::$nic, "address-gibberish-fake");
        $this->assertEquals(sizeof($result), 1);
        $result = PatientDetails::searchPatient( null, "fake-gibberish-nic", "address-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
    }

    public function testSearchByNICAndNameAndAddress()
    {
        $result = PatientDetails::searchPatient("Patient", self::$nic, "City");
        $this->assertEquals(sizeof($result), 1);
        $result = PatientDetails::searchPatient( "Patient", "fake-gibberish-nic", "City");
        $this->assertNotEquals(sizeof($result), 0);
        $result = PatientDetails::searchPatient("Patient", self::$nic, "address-gibberish-fake");
        $this->assertEquals(sizeof($result), 1);
        $result = PatientDetails::searchPatient( "Patient", "fake-gibberish-nic", "address-gibberish-fake");
        $this->assertNotEquals(sizeof($result), 0);

        $result = PatientDetails::searchPatient("fake-gibberish-name", self::$nic, "City");
        $this->assertEquals(sizeof($result), 1);
        $result = PatientDetails::searchPatient( "fake-gibberish-name", "fake-gibberish-nic", "City");
        $this->assertNotEquals(sizeof($result), 0);
        $result = PatientDetails::searchPatient("fake-gibberish-name", self::$nic, "address-gibberish-fake");
        $this->assertEquals(sizeof($result), 1);
        $result = PatientDetails::searchPatient( "fake-gibberish-name", "fake-gibberish-nic", "address-gibberish-fake");
        $this->assertEquals(sizeof($result), 0);
    }
}
