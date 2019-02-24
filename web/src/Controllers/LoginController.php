<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\AccountRejectedException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Models\AccountSession\LoginService;
use Pulse\StaticLogger;

class LoginController extends BaseController
{
    /**
     */
    public function post()
    {
        $accountId = $this->getRequest()->getBodyParameter('account');
        $password = $this->getRequest()->getBodyParameter('password');

        if ($accountId == null || $password == null) {
            StaticLogger::loggerWarn("AccountID or Password null when Login in a user by POST");
            header("Location: http://$_SERVER[HTTP_HOST]/login");
            exit;
        }

        try {
            $session = LoginService::logInSession($accountId, $password);
        } catch (AccountNotExistException $ex) {
            $message = "Account $accountId Not Found";
            header("Location: http://$_SERVER[HTTP_HOST]/login?error=$message");
            exit;
        } catch (InvalidDataException $e) {
            $message = "Account $accountId login Error";
            header("Location: http://$_SERVER[HTTP_HOST]/login?error=$message");
            exit;
        } catch (AccountRejectedException $e) {
            $message = "Your account $accountId was rejected. Please contact system administrators for further details.";
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
        $account = $this->getCurrentAccount();

        if ($account == null) {
            $this->render('LoginPage.html.twig', array(), $account);
        } else {
            $this->render('AlreadyLoggedIn.html.twig', array(), $account);
        }
    }
}