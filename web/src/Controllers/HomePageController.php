<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\UserNotExistException;
use Pulse\Models\LoginService;

class HomePageController extends BaseController
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
            if ($session != null) {
                $userId = $session->getSessionUserId();
            } else {
                $userId = null;
            }
        } catch (UserNotExistException $e) {
            $userId = null;
        }

        $this->render('ComingSoon.html.twig', array(
            "site" => "http://$_SERVER[HTTP_HOST]",
            'user_id' => $userId
        ));
    }
}