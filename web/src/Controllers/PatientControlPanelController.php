<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components\Logger;
use Pulse\Components\Utils;
use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Prescription\Medication;
use Pulse\Models\Prescription\Prescription;

class PatientControlPanelController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        parent::loadOnlyIfUserIsOfType(Patient::class, 'ControlPanelPatientPage.html.twig');
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getPatientTimelineIframe()
    {
        $currentAccount = $this->getCurrentAccount();
        try {
            if ($currentAccount instanceof Patient) {
                $prescriptions = $currentAccount->getPrescriptions();
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
                Logger::log(Utils::array2string($parsedPrescriptions));


                $this->render('iframe/PatientTimelineIFrame.htm.twig', array('prescriptions' => $parsedPrescriptions), $currentAccount);
            } else {
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
            }
        } catch (InvalidDataException $e) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/404");
        }
    }
}