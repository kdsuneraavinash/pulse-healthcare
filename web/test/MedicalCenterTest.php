<?php

namespace PulseTest;

use DB;
use PHPUnit\Framework\TestCase;
use Pulse\Models\MedicalCenter\MedicalCenter;

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
        DB::delete('accounts', "account_id = %s", MedicalCenterTest::$accountId);
    }

    /**
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\PHSRCAlreadyInUse
     */

    public function testRequestRegistration()
    {
        MedicalCenter::requestRegistration(
            MedicalCenterTest::$accountId,
            MedicalCenterTest::$name,
            MedicalCenterTest::$phsrc,
            MedicalCenterTest::$email,
            MedicalCenterTest::$fax,
            MedicalCenterTest::$phoneNumber,
            MedicalCenterTest::$address,
            MedicalCenterTest::$postalCode
        );
        $query = DB::query("SELECT account_id FROM medical_centers WHERE account_id=%s", MedicalCenterTest::$accountId);
        $this->assertNotNull($query);
    }
}
