<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\UserNotExistException;
use Pulse\Models\LoginService;

class ProfilePageController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        try {
            $session = LoginService::continueSession();
            if ($session == null) {
                header("Location: http://$_SERVER[HTTP_HOST]/");
                exit;
            }
        } catch (UserNotExistException $e) {
            header("Location: http://$_SERVER[HTTP_HOST]");
            exit;
        }

        $this->render('ProfilePage.html.twig', array(
            "site" => "http://$_SERVER[HTTP_HOST]",
            'user_id' => $session->getSessionUserId()
        ));
    }
}