<?php declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

use phpDocumentor\Reflection\Types\Array_;
use Pulse\Components\Database;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\Models\Enums\AccountType;
use Pulse\Models\Enums\VerificationState;
use Pulse\Models\Exceptions;
use Pulse\Models\Interfaces\IFavouritable;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Patient\PatientDetails;

class MedicalCenter extends Account implements IFavouritable
{
    private $medicalCenterDetails;
    private $verificationState;

    /**
     * MedicalCenter constructor.
     * @param string $accountId
     * @param int|null $verificationState
     * @param MedicalCenterDetails $medicalCenterDetails
     * @param bool $ignoreErrors
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     */
    protected function __construct(string $accountId, ?int $verificationState,
                                   MedicalCenterDetails $medicalCenterDetails, bool $ignoreErrors = false)
    {
        parent::__construct($accountId, AccountType::MedicalCenter);
        $this->medicalCenterDetails = $medicalCenterDetails;
        if ($verificationState === null) {
            // Need to fetch from database
            $query = Database::queryFirstRow("SELECT verified from medical_centers WHERE account_id=:account_id",
                array('account_id' => $accountId));

            if ($query == null) {
                throw new Exceptions\AccountNotExistException($accountId);
            }
            $this->verificationState = (int)$query['verified'];
            if (!$ignoreErrors && $this->getVerificationState() == VerificationState::Rejected) {
                throw new Exceptions\AccountRejectedException($accountId);
            }
        } else {
            $this->verificationState = $verificationState;
        }
    }

    /**
     * @param string $accountId
     * @param MedicalCenterDetails $medicalCenterDetails
     * @param string $password
     * @return MedicalCenter
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\AlreadyLoggedInException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\PHSRCAlreadyInUse
     */
    public static function requestRegistration(string $accountId, MedicalCenterDetails $medicalCenterDetails,
                                               string $password): MedicalCenter
    {
        $medicalCenter = new MedicalCenter($accountId, VerificationState::Default, $medicalCenterDetails);
        $medicalCenter->saveInDatabase();
        LoginService::signUpSession($accountId, $password);
        // TODO: Add code to request verification
        return $medicalCenter;
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\PHSRCAlreadyInUse
     */
    protected function saveInDatabase()
    {
        $this->validateFields();

        parent::saveInDatabase();
        Database::insert('medical_centers', array(
            'account_id' => parent::getAccountId(),
            'verified' => (string)$this->getVerificationState()
        ));
        $this->getMedicalCenterDetails()->saveInDatabase(parent::getAccountId());
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\PHSRCAlreadyInUse
     */
    private function validateFields()
    {
        $detailsValid = $this->medicalCenterDetails->validate();
        if (!$detailsValid) {
            throw new Exceptions\InvalidDataException("Server side validation failed.");
        }
        parent::checkWhetherAccountIDExists();
        $this->checkWhetherPHSRCExists();
    }

    /**
     * @throws Exceptions\PHSRCAlreadyInUse
     */
    private function checkWhetherPHSRCExists()
    {
        $existingMedicalCenter = Database::queryFirstRow("SELECT account_id from medical_center_details WHERE phsrc=:phsrc",
            array('phsrc' => $this->medicalCenterDetails->getPhsrc()));

        if ($existingMedicalCenter != null) {
            throw new Exceptions\PHSRCAlreadyInUse($existingMedicalCenter['account_id']);
        }
    }

    /**
     * @param PatientDetails $patientDetails
     * @return string
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     */

    public function createPatientAccount(PatientDetails $patientDetails): string
    {
        return Patient::register($patientDetails);
    }

    /**
     * @param DoctorDetails $doctorDetails
     * @return string
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\InvalidDataException
     * @throws Exceptions\SLMCAlreadyInUse
     */
    public function createDoctorAccount(DoctorDetails $doctorDetails): string
    {
        return Doctor::register($doctorDetails);
    }

    public static function searchDoctor($slmc_id,$name,$region,$category)
    {


        /**
         *
         * 1.If empty keywords return null
         * 2.If perfect match with id, return only the result
         * 3.Else return results on priority
         *
         *
         *
         */


        if ($slmc_id==null && empty($name) && $region==null && $category==null){
            return false;
        }

        //Weights of keywords
        $slmc_weight=5;
        $name_weight=4;
        $region_weight=3;


        $slmcSQL = array();
        $nameSQL = array();
        $regionSQL = array();


        if($slmc_id!=null){
            $slmcSQL[]="if (slmc_id LIKE '%".$slmc_id."%',{$slmc_weight},0)";

        }


        if($name!=null){
            foreach($name as $key) {
                $nameSQL[] = "if (full_name LIKE '%".$key."%',{$name_weight},0)";

            }
        }



//        if($region){
//            $regionSQL[]="if (region LIKE '%".$region."%',{$region_weight},0)";
//        }



        if (empty($slmcSQL)){
            $slmcSQL[] = 0;
        }
        if (empty($nameSQL)){
            $nameSQL[] = 0;
        }
        if (empty($regionSQL)){
            $regionSQL[] = 0;
        }


        if($slmc_id==null &&  empty($name) && $category!="null"){

            $query ="SELECT display_name,account_id,nic,full_name,slmc_id,
            email,phone_number,category
            FROM doctor_details
            WHERE category = '$category'
            LIMIT 25";

            $result=Database::query($query,array());

            //print_r($result);
            return $result;


        }else if(($slmc_id!=null || ! empty($name)) && $category!="null"){
            $query ="SELECT display_name,account_id,nic,full_name,slmc_id,
            email,phone_number,category,
            (
                (-- calculate slmc weight
                ".implode(" + ", $slmcSQL)."
                )+
                (-- calculate name weight
                ".implode(" + ", $nameSQL)."
                )
            ) as relevance
            FROM doctor_details
            WHERE category = '$category'
            HAVING relevance > 0
            ORDER BY relevance DESC
            LIMIT 25";

            $result=Database::query($query,array());
            //print_r($result);
            return $result;
        }else if(($slmc_id!= null || ! empty($name)) && $category=="null"){
            $query ="SELECT display_name,account_id,nic,full_name,slmc_id,
            email,phone_number,category,
            (
                (-- calculate slmc weight
                ".implode(" + ", $slmcSQL)."
                )+
                (-- calculate name weight
                ".implode(" + ", $nameSQL)."
                )
            ) as relevance
            FROM doctor_details
            HAVING relevance > 0
            ORDER BY relevance DESC
            LIMIT 25";

            $result=Database::query($query,array());
            //print_r($result);
            return $result;
        }else{
            return false;
        }

        //return ($result != null);

    }

    public function searchPatient()
    {
        // TODO: implementation of searchPatient() function
    }

    /**
     * @return MedicalCenterDetails
     */
    public function getMedicalCenterDetails(): MedicalCenterDetails
    {
        return $this->medicalCenterDetails;
    }

    /**
     * @return int
     */
    public function getVerificationState(): int
    {
        return $this->verificationState;
    }



































}
