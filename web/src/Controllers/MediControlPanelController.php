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
        parent::loadOnlyIfUserIsOfType(MedicalCenter::class,
            'ControlPanelMediPage.html.twig', "http://$_SERVER[HTTP_HOST]/405");
    }
}