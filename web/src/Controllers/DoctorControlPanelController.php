<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components\Logger;
use Pulse\Models\Doctor\Doctor;

class DoctorControlPanelController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        parent::loadOnlyIfUserIsOfType(Doctor::class, 'ControlPanelDoctorPage.htm.twig');
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getDoctorDashboardIframe()
    {
        parent::loadOnlyIfUserIsOfType(Doctor::class, 'iframe/AdminDashboardIFrame.htm.twig');
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getDoctorCreatePrescriptionSearchPatientIframe()
    {
        $prescriptionId = $this->httpHandler()->getParameter('prescription_id');

        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof Doctor) {
            if ($prescriptionId == null){
                $this->render('iframe/CreatePrescriptionSearchPatient.htm.twig', array(),
                    $currentAccount);
            }else{
                $this->render('iframe/CreatePrescriptionSearchPatient.htm.twig',
                    array('prescription_id' => $prescriptionId),
                    $currentAccount);
            }

        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getDoctorCreatePrescriptionIframe()
    {
        $patientId = $this->httpHandler()->getParameter('patient');
        $patientName = $this->httpHandler()->getParameter('name');

        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof Doctor) {
            $this->render('iframe/DoctorCreatePrescription.htm.twig',
                array('patient_id' => $patientId,
                    'patient_name' => $patientName),
                $currentAccount);
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getDoctorRegisterPatientIframe()
    {
        parent::loadOnlyIfUserIsOfType(Doctor::class, 'iframe/MedicalCenterCreatePatient.htm.twig');
    }
}