<?php declare(strict_types=1);

namespace Pulse\Controllers\API;

use Pulse\Controllers\BaseController;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Exceptions;
use Pulse\Models\Patient\Patient;

class LoginController extends BaseController
{

    /**
     * @param string $message
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function echoError(string $message)
    {
        $this->render('api/Login.json.twig',
            array('message' => $message, 'ok' => 'false'),
            null);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function login()
    {
        $accountId = $this->httpHandler()->getParameter('account');
        $password = $this->httpHandler()->getParameter('password');

        if ($accountId == null || $password == null) {
            $this->echoError('Account ID or Password was empty');
            return;
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
            $this->echoError($message);
            return;
        }

        /// Redirect to correct location
        $account = $session->getSessionAccount();
        if (!($account  instanceof Patient)){
            $this->echoError("Account Type is not of Patient");
            $session->closeSession();
            return;
        }
        $this->render('api/Login.json.twig', array('message' => "Logged In as {$account->getAccountId()}", 'ok' => 'true'), null);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function logout()
    {
        LoginService::signOutSession();
        $this->render('api/Login.json.twig', array('message' => "Logged Out", 'ok' => 'true'), null);
        return;
    }
}