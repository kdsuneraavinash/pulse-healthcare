<?php declare(strict_types=1);

namespace Pulse\Controllers;

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
        parent::loadOnlyIfUserIsOfType(MedicalCenter::class, 'iframe/MedicalCenterCreateDoctor.htm.twig');
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getMediRegisterPatientIframe()
    {
        parent::loadOnlyIfUserIsOfType(MedicalCenter::class, 'iframe/MedicalCenterCreatePatient.htm.twig');
    }
}