<?php declare(strict_types=1);

namespace Pulse\Controllers\Test;

use Pulse\Controllers\BaseController;

class PatientController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $accountId = $this->getCurrentAccountId();
        $this->render('PatientController.html.twig', array(), $accountId);
    }
}