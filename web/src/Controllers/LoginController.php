<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\BaseController;
use Pulse\Exceptions\UserNotExistException;
use Pulse\Models\LoginService;

class LoginController extends BaseController
{
    /**
     */
    public function post()
    {
        $userId = $this->getRequest()->getBodyParameter('user');
        $password = $this->getRequest()->getBodyParameter('password');

        if ($userId == null || $password == null) {
            echo "POST Request required";
            exit;
        }

        try {
            $session = LoginService::logInSession($userId, $password);
        } catch (UserNotExistException $ex) {
            echo "User $userId Not Found";
            exit;
        }

        if ($session == null) {
            echo "Invalid Credentials $userId: $password";
            exit;
        }

        header("Location: http://$_SERVER[HTTP_HOST]/profile");
        exit;
    }

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
                $this->render('AlreadyLoggedIn.html.twig', array(
                    'user' => $session->getSessionUserId(),
                    'redirect' => "http://$_SERVER[HTTP_HOST]/login",
                    'site' => "http://$_SERVER[HTTP_HOST]",
                    'user_id' => $session->getSessionUserId()
                ));
                return;
            }
        } catch (UserNotExistException $ex) {
            LoginService::signOutSession();
        }
        $this->render('LoginPage.html.twig', array(
            'site' => "http://$_SERVER[HTTP_HOST]"
        ));
    }
}