<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\StaticLogger;
use Pulse\Utils;

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
            StaticLogger::loggerWarn("Unautherized user " . Utils::getClientIP() .
                " tried to access Profile page.");
            exit;
        } else {
            $this->render('ProfilePage.html.twig', array(), $accountId);
        }
    }
}