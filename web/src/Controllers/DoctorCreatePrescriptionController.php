<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components;
use Pulse\Components\Utils;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Exceptions\AccountNotExistException;
use Pulse\Models\Exceptions\AccountRejectedException;
use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Prescription\Medication;
use Pulse\Models\Prescription\Prescription;

class DoctorCreatePrescriptionController extends BaseController
{
    public function post()
    {
        $currentAccount = $this->getCurrentAccount();

        if ($currentAccount instanceof Doctor) {
            $doctorId = $currentAccount->getAccountId();
            $patientId = $this->httpHandler()->postParameter('patient');
            $medications = $this->httpHandler()->postParameter('medications');

            $medicationsArr = array();

            foreach ($medications as $item) {

                $name = $item['name'];
                $dose = $item['dose'];
                $frequency = $item['frequency'];
                $time = $item['time'];
                $comment = $item['comment'];

                $medication = new Medication(null, null, $name, $dose, $frequency, $time, $comment);
                array_push($medicationsArr, $medication);
            };

            $prescription = new Prescription(null, $patientId, $doctorId, $medicationsArr);
             try {
                 $prescription->saveInDatabase();
             } catch (InvalidDataException $error) {
                 $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/doctor/create/prescription?error=$error");
             }

            // TODO: Uncomment following line
            // $prescriptionId = $prescription->getPrescriptionId();
            // TODO: Comment following line
            $prescriptionId = $patientId . "_PRESCRIPTION";
             
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/doctor/create/prescription?prescription=$prescriptionId");
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }

    public function postSearchPatient(){
        $currentAccount = $this->getCurrentAccount();

        if ($currentAccount instanceof Doctor) {
            $patientId = $this->httpHandler()->postParameter('patient');
            try {
                $account = Account::retrieveAccount($patientId, true);
                if (!($account instanceof Patient)){
                    throw new AccountNotExistException($patientId);
                }
            } catch (AccountNotExistException|AccountRejectedException|InvalidDataException $e) {
                $error = "Account ID Invalid";
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/doctor/create/search?error=$error");
                return;
            }

            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/doctor/create/prescription?patient=$patientId");
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }
}