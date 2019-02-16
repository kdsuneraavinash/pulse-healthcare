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
        $userId = $this->getCurrentUserId();

        if ($userId == null) {
            $this->render('LoginPage.html.twig', array(), $userId);
        } else {
            $this->render('AlreadyLoggedIn.html.twig', array(), $userId);
        }
    }
}