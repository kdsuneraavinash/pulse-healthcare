<?php declare(strict_types=1);

namespace Pulse\Controllers\API;

use Pulse\Controllers\BaseController;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Prescription\Medication;
use Pulse\Models\Prescription\Prescription;

class TimelineController extends BaseController
{
    /**
     * @param string $message
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function echoError(string $message)
    {
        $this->render('api/Login.json.twig',
            array('message' => $message, 'ok' => 'false'),
            null);
    }


    /**
     * @param Account|null $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function timeline(?Account $currentAccount)
    {
        try {
            if ($currentAccount instanceof Patient) {
                $this->showTimelineOfPatient($currentAccount);
            } else {
                $this->echoError('Current account is not a patient');
                return;
            }
        } catch (InvalidDataException $e) {
            $this->echoError("Invalid Data Entries: {$e->getMessage()}");
            return;
        }
    }

    /**
     * @param Patient $patient
     * @throws InvalidDataException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function showTimelineOfPatient(Patient $patient)
    {
        $prescriptions = $patient->getPrescriptions();
        if (sizeof($prescriptions) == 0) {
            throw new InvalidDataException("No Prescriptions");
        }
        $parsedPrescriptions = array();
        foreach ($prescriptions as $prescription) {
            $parsedMedications = array();
            if ($prescription instanceof Prescription) {
                foreach ($prescription->getMedications() as $medication) {
                    if ($medication instanceof Medication) {
                        $parsedMedication = array(
                            'id' => $medication->getMedicationId(),
                            'name' => $medication->getName(),
                            'dose' => $medication->getDose(),
                            'frequency' => $medication->getFrequency(),
                            'time' => $medication->getTime(),
                            'comment' => $medication->getComment(),
                        );
                        array_push($parsedMedications, $parsedMedication);
                    }
                }
                $parsedPrescription = array(
                    'doctor' => $prescription->getDoctorId(),
                    'id' => $prescription->getPrescriptionId(),
                    'date' => $prescription->getDate(),
                    'patient' => $prescription->getPatientId(),
                    'medications' => $parsedMedications
                );
                array_push($parsedPrescriptions, $parsedPrescription);
            }
        }

        $this->render('api/Timeline.json.twig', array('prescriptions' => $parsedPrescriptions, 'message'=> 'Timeline Loaded', 'ok'=>'true'), $patient);
        return;
    }
}