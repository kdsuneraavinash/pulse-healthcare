<?php declare(strict_types=1);

namespace Pulse\Controllers;

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

            if ($patientId == null){
                $this->httpHandler()->setContent("E|No patient information submitted. Please resubmit.");
                return;
            }

            if ($medications == null || !is_array($medications)){
                $this->httpHandler()->setContent("E|No medications submitted. You have to enter at least one medication to submit.");
                return;
            }

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
            } catch (InvalidDataException|AccountNotExistException $error) {
                $errorMessage = $error->getMessage();
                $this->httpHandler()->setContent("E|$errorMessage");
                return;
            }

            $prescriptionId = $prescription->getPrescriptionId();

            $this->httpHandler()->setContent("K|$prescriptionId");
        } else {
            $this->httpHandler()->setContent("E|Method Not Allowed. You have been logged out. Please re-login.");
        }
    }

    public function postSearchPatient()
    {
        $currentAccount = $this->getCurrentAccount();

        if ($currentAccount instanceof Doctor) {
            $patientId = $this->httpHandler()->postParameter('patient');
            try {
                $account = Account::retrieveAccount($patientId, true);
                if ($account instanceof Patient) {
                    $patientName = $account->getPatientDetails()->getName();
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/doctor/create/prescription?patient=$patientId&name=$patientName");
                }else{
                    throw new AccountNotExistException($patientId);
                }
            } catch (AccountNotExistException|AccountRejectedException|InvalidDataException $e) {
                $error = "Account ID Invalid";
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/doctor/create/search?error=$error");
                return;
            }
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }
}