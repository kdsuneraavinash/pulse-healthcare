<?php declare(strict_types=1);

namespace Pulse\Controllers;

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
            header("Location: http://$_SERVER[HTTP_HOST]/login");
            exit;
        }

        try {
            $session = LoginService::logInSession($userId, $password);
        } catch (UserNotExistException $ex) {
            $message =  "User $userId Not Found";
            header("Location: http://$_SERVER[HTTP_HOST]/login?error=$message");
            exit;
        }

        if ($session == null) {
            $message =  "Invalid Username/Password";
            header("Location: http://$_SERVER[HTTP_HOST]/login?error=$message");
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
        $userId = $this->getCurrentUserId();

        if ($userId == null) {
            $this->render('LoginPage.html.twig', array(), $userId);
        } else {
            $this->render('AlreadyLoggedIn.html.twig', array(), $userId);
        }
    }
}