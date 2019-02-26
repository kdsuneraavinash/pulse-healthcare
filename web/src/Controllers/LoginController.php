<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\AccountRejectedException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Admin\Admin;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\StaticLogger;

class LoginController extends BaseController
{
    /**
     */
    public function post()
    {
        $accountId = $this->httpHandler()->postParameter('account');
        $password = $this->httpHandler()->postParameter('password');

        if ($accountId == null || $password == null) {
            StaticLogger::loggerWarn("AccountID or Password null when Login in a user by POST");
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/login");
        }

        $session = null;
        try {
            $session = LoginService::logInSession($accountId, $password);
            if ($session == null) {
                $message = "Invalid Username/Password";
            }
        } catch (AccountNotExistException $ex) {
            $message = "Account $accountId Not Found";
        } catch (InvalidDataException $e) {
            $message = "Account $accountId login Error";
        } catch (AccountRejectedException $e) {
            $message = "Your account $accountId was rejected. Please contact system administrators for further details.";
        }

        if (isset($message)){
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/login?error=$message");
        }

        /// Redirect to correct location
        $currentAccount = $session->getSessionAccount();
        if ($currentAccount instanceof Admin){
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/admin");
        }else if ($currentAccount instanceof MedicalCenter){
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/med_center");
        }else{
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/profile");
        }
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