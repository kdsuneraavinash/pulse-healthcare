<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\Admin\Admin;

class AdminControlPanelController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        parent::loadOnlyIfUserIsOfType(Admin::class,
            'ControlPanelAdminPage.html.twig', "http://$_SERVER[HTTP_HOST]");
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminDashboardIframe()
    {
        parent::loadOnlyIfUserIsOfType(Admin::class,
            'iframe/AdminDashboardIFrame.htm.twig', "http://$_SERVER[HTTP_HOST]/404");
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminVerifyMedicalCentersIframe()
    {
        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof Admin) {
            $this->render('iframe/AdminVerifyMedicalCentersIFrame.htm.twig', array(
                'medical_centers' => $currentAccount->retrieveMedicalCentersList()
            ), $currentAccount);
        } else {
            header("http://$_SERVER[HTTP_HOST]/404");
            exit;
        }
    }
}