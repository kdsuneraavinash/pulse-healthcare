<?php declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

use DB;
use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\PHSRCAlreadyInUse;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\Interfaces\IFavouritable;

class MedicalCenter extends Account implements IFavouritable
{
    private $medicalCenterDetails;

    /**
     * MedicalCenter constructor.
     * @param string $accountId
     * @param string $name
     * @param string $phsrc
     * @param string $email
     * @param string $fax
     * @param string $phoneNumber
     * @param string $address
     * @param string $postalCode
     */
    protected function __construct(string $accountId, string $name, string $phsrc, string $email, string $fax,
                                   string $phoneNumber, string $address, string $postalCode)
    {
        parent::__construct($accountId);
        $this->medicalCenterDetails = new MedicalCenterDetails(
            $name, $phsrc, $email, $fax, $phoneNumber, $address, $postalCode
        );
    }

    /**
     * @throws PHSRCAlreadyInUse
     * @throws AccountAlreadyExistsException
     */
    private function saveInDatabase()
    {
        $existingAccount = DB::queryFirstRow("SELECT account_id from accounts where account_id=%s",
            $this->accountId);
        if ($existingAccount != null) {
            throw new AccountAlreadyExistsException($existingAccount['account_id']);
        }

        $existingMedicalCenter = DB::queryFirstRow("SELECT account_id from medical_center_details where phsrc=%s",
            $this->accountId);
        if ($existingMedicalCenter != null) {
            throw new PHSRCAlreadyInUse($existingMedicalCenter['account_id']);
        }

        DB::insert('accounts', array(
            'account_id' => $this->accountId,
            'account_type' => "med_center"
        ));
        DB::insert('medical_centers', array(
            'account_id' => $this->accountId,
            'verified' => false
        ));
        DB::insert('medical_center_details', array(
            'account_id' => $this->accountId,
            'name' => $this->medicalCenterDetails->getName(),
            'phsrc' => $this->medicalCenterDetails->getPhsrc(),
            'email' => $this->medicalCenterDetails->getEmail(),
            'fax' => $this->medicalCenterDetails->getFax(),
            'phone_number' => $this->medicalCenterDetails->getPhoneNumber(),
            'address' => $this->medicalCenterDetails->getAddress(),
            'postal_code' => $this->medicalCenterDetails->getPostalCode()
        ));
    }

    /**
     * @param string $accountId
     * @param string $name
     * @param string $phsrc
     * @param string $email
     * @param string $fax
     * @param string $phoneNumber
     * @param string $address
     * @param string $postalCode
     * @throws AccountAlreadyExistsException
     * @throws PHSRCAlreadyInUse
     */
    public static function requestRegistration(string $accountId, string $name, string $phsrc, string $email, string $fax,
                                               string $phoneNumber, string $address, string $postalCode)
    {
        $medicalCenter = new MedicalCenter($accountId, $name, $phsrc,
            $email, $fax, $phoneNumber, $address, $postalCode);
        $medicalCenter->saveInDatabase();
    }

    public function createPatientAccount()
    {
        // implementation of createPatientAccount() function
    }

    public function createDoctorAccount()
    {
        // implementation of createDoctorAccount() function
    }

    public function removeUser()
    {
        // implementation of removeUser() function
    }


    public function searchDoctor()
    {
        // implementation of searchDoctor() function
    }

    public function searchPatient()
    {
        // implementation of searchPatient() function

    }

    public function getMedicalCenterDetails(): MedicalCenterDetails
    {
        return $this->medicalCenterDetails;
    }
}
