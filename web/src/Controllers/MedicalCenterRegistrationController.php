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
        $userId = $this->getCurrentUserId();
        if ($userId == null) {
            $this->render('MedicalCenterRegistration.html.twig', array(), $userId);
        } else {
            $this->render('AlreadyLoggedIn.html.twig',
                array('redirect' => "http://$_SERVER[HTTP_HOST]/medi"), $userId);
        }
    }
}