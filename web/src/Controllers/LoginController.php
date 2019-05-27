<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Admin\Admin;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Exceptions;

class LoginController extends BaseController
{
    public function post()
    {
        $accountId = $this->httpHandler()->postParameter('account');
        $password = $this->httpHandler()->postParameter('password');

        if ($accountId == null || $password == null) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/login");
        }

        $session = null;
        try {
            $session = LoginService::logInSession($accountId, $password);
            if ($session == null) {
                $message = "Invalid Username/Password";
            }
        } catch (Exceptions\AccountNotExistException $ex) {
            $message = "Account $accountId Not Found";
        } catch (Exceptions\InvalidDataException $e) {
            $message = "Account $accountId login Error";
        } catch (Exceptions\AccountRejectedException $e) {
            $message = "Your account $accountId was rejected. Please contact system administrators for further details.";
        }

        if (isset($message)) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/login?error=$message");
        }

        /// Redirect to correct location
        $currentAccount = $session->getSessionAccount();
        if ($currentAccount instanceof Admin) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/admin");
        } else if ($currentAccount instanceof MedicalCenter) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/med_center");
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/profile");
        }
    }

    /**
     * @param Account|null $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get(?Account $currentAccount)
    {
        if ($currentAccount == null) {
            $this->render('LoginPage.html.twig', array(), $currentAccount);
        } else {
            $this->render('AlreadyLoggedIn.html.twig', array(), $currentAccount);
        }
    }
}