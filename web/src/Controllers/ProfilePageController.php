<?php declare(strict_types=1);

namespace Pulse\Controllers;

class ProfilePageController extends BaseController
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
            header("Location: http://$_SERVER[HTTP_HOST]");
            exit;
        } else {
            $this->render('ProfilePage.html.twig', array(), $accountId);
        }
    }
}