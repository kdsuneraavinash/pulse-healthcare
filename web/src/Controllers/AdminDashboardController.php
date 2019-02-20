<?php declare(strict_types=1);

namespace Pulse\Controllers;

class AdminDashboardController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $accountId = $this->getCurrentAccountId();
        $this->render('ControlPanelPage.html.twig', array(), $accountId);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAdminDashboardIframe(){
        $accountId = $this->getCurrentAccountId();
        $this->render('iframe/AdminDashboardIFrame.htm.twig', array(), $accountId);
    }
}