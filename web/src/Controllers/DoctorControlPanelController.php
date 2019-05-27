<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\Doctor\Doctor;

class DoctorControlPanelController extends BaseController
{
    /**
     * @param Doctor $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get(Doctor $currentAccount)
    {
        $this->renderWithNoContext('ControlPanelDoctorPage.twig', $currentAccount);
    }

    /**
     * @param Doctor $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getDoctorCreatePrescriptionSearchPatientIframe(Doctor $currentAccount)
    {
        $prescriptionId = $this->httpHandler()->getParameter('prescription_id');
        if ($prescriptionId == null) {
            $this->renderWithNoContext('iframe/CreatePrescriptionSearchPatient.twig', $currentAccount);
        } else {
            $this->render('iframe/CreatePrescriptionSearchPatient.twig',
                array('prescription_id' => $prescriptionId),
                $currentAccount);
        }

    }

    /**
     * @param Doctor $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getDoctorCreatePrescriptionIframe(Doctor $currentAccount)
    {
        $patientId = $this->httpHandler()->getParameter('patient');
        $patientName = $this->httpHandler()->getParameter('name');

        $this->render('iframe/DoctorCreatePrescription.twig',
            array('patient_id' => $patientId,
                'patient_name' => $patientName),
            $currentAccount);

    }
}