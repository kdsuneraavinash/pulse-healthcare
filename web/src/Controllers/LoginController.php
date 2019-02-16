<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountNotExistException;
use Pulse\Models\AccountSession\LoginService;

class LoginController extends BaseController
{
    /**
     */
    public function post()
    {
        $accountId = $this->getRequest()->getBodyParameter('account');
        $password = $this->getRequest()->getBodyParameter('password');

        if ($accountId == null || $password == null) {
            header("Location: http://$_SERVER[HTTP_HOST]/login");
            exit;
        }

        try {
            $session = LoginService::logInSession($accountId, $password);
        } catch (AccountNotExistException $ex) {
            $message = "Account $accountId Not Found";
            header("Location: http://$_SERVER[HTTP_HOST]/login?error=$message");
            exit;
        }

        if ($session == null) {
            $message = "Invalid Username/Password";
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
        $accountId = $this->getCurrentAccountId();

        if ($accountId == null) {
            $this->render('LoginPage.html.twig', array(), $accountId);
        } else {
            $this->render('AlreadyLoggedIn.html.twig', array(), $accountId);
        }
    }
}