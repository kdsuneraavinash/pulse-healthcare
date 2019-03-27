<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components;
use Pulse\Components\Utils;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Exceptions\InvalidDataException;
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
                 $this->httpHandler()->setContent("http://$_SERVER[HTTP_HOST]/control/doctor/create/prescription?error=$error");
             }

            // TODO: Uncomment following line
            // $prescriptionId = $prescription->getPrescriptionId();
            // TODO: Comment following line
            $prescriptionId = $patientId . "_PRECRIPTION";
             
            $this->httpHandler()->setContent("http://$_SERVER[HTTP_HOST]/control/doctor/create/prescription?prescription=$prescriptionId");
        } else {
            $this->httpHandler()->setContent("http://$_SERVER[HTTP_HOST]/405");
        }
    }
}