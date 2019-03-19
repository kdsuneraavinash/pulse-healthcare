<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\Enums\VerificationState;
use Pulse\Models\MedicalCenter\MedicalCenter;

class MediControlPanelController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        parent::loadOnlyIfUserIsOfType(MedicalCenter::class, 'ControlPanelMediPage.htm.twig');
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getMediDashboardIframe()
    {
        parent::loadOnlyIfUserIsOfType(MedicalCenter::class, 'iframe/AdminDashboardIFrame.htm.twig');
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getMediRegisterDoctorIframe()
    {
        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof MedicalCenter) {
            if ($currentAccount->getVerificationState() == VerificationState::Verified){
                $this->render('iframe/MedicalCenterCreateDoctor.htm.twig', array(), $currentAccount);
            }else{
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/lock");
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
    public function getMediRegisterPatientIframe()
    {
        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof MedicalCenter) {
            if ($currentAccount->getVerificationState() == VerificationState::Verified){
                $this->render('iframe/MedicalCenterCreatePatient.htm.twig', array(), $currentAccount);
            }else{
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/lock");
            }
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }
}