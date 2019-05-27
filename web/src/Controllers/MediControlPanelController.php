<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\Enums\VerificationState;
use Pulse\Models\MedicalCenter\MedicalCenter;

class MediControlPanelController extends BaseController
{
    /**
     * @param MedicalCenter $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get(MedicalCenter $currentAccount)
    {
        $this->renderWithNoContext('ControlPanelMediPage.twig', $currentAccount);
    }

    /**
     * @param MedicalCenter $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getMediRegisterDoctorIframe(MedicalCenter $currentAccount)
    {
        if ($currentAccount->getVerificationState() == VerificationState::Verified) {
            $this->render('iframe/MedicalCenterCreateDoctor.twig', array(), $currentAccount);
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/lock");
        }

    }

    /**
     * @param MedicalCenter $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getMediRegisterPatientIframe(MedicalCenter $currentAccount)
    {
        if ($currentAccount->getVerificationState() == VerificationState::Verified) {
            $this->render('iframe/MedicalCenterCreatePatient.twig', array(), $currentAccount);
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/lock");
        }
    }
}