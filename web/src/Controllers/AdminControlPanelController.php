<?php declare(strict_types=1);

namespace Pulse\Controllers;

class AdminControlPanelController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $account = $this->getCurrentAccount();
        $this->render('ControlPanelAdminPage.html.twig', array(), $account);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminDashboardIframe()
    {
        $account = $this->getCurrentAccount();
        $this->render('iframe/AdminDashboardIFrame.htm.twig', array(), $account);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminVerifyMedicalCentersIframe()
    {
        $account = $this->getCurrentAccount();
        $this->render('iframe/AdminVerifyMedicalCentersIFrame.htm.twig', array(), $account);
    }
}