<?php declare(strict_types=1);

namespace Pulse\Controllers;

class MedicalCenterRegistrationController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $accountId = $this->getCurrentAccountId();
        if ($accountId == null) {
            $this->render('MedicalCenterRegistration.html.twig', array(), $accountId);
        } else {
            $this->render('AlreadyLoggedIn.html.twig', array(), $accountId);
        }
    }
}