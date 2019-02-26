<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\SLMCAlreadyInUse;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\StaticLogger;
use Pulse\Utils;

class MediControlPanelController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        parent::loadOnlyIfUserIsOfType(MedicalCenter::class,
            'ControlPanelMediPage.htm.twig', "http://$_SERVER[HTTP_HOST]/405");
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getMediDashboardIframe()
    {
        parent::loadOnlyIfUserIsOfType(MedicalCenter::class,
            'iframe/AdminDashboardIFrame.htm.twig', "http://$_SERVER[HTTP_HOST]/405");
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getMediRegisterDoctorIframe()
    {
        parent::loadOnlyIfUserIsOfType(MedicalCenter::class,
            'iframe/MedicalCenterCreateDoctor.htm.twig', "http://$_SERVER[HTTP_HOST]/405");
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getMediRegisterPatientIframe()
    {
        parent::loadOnlyIfUserIsOfType(MedicalCenter::class,
            'iframe/MedicalCenterCreatePatient.htm.twig', "http://$_SERVER[HTTP_HOST]/405");
    }
}