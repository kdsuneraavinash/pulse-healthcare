<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\HttpHandler;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Exceptions\AccountNotExistException;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Twig_Environment;

abstract class BaseController
{
    private $renderer;

    /**
     * Activates a Controller with HTTP objects and renderer.
     * @param BaseController $controller Controller
     * @param Twig_Environment $renderer HTML Rendering Object
     */
    public static function activate(BaseController $controller,
                                    Twig_Environment $renderer)
    {
        $controller->renderer = $renderer;
    }

    /**
     * @return HttpHandler request object
     */
    protected function httpHandler(): HttpHandler
    {
        return HttpHandler::getInstance();
    }

    /**
     * @param string $template Template file name (without extension)
     * @param array $context Values to pass into file
     * @param Account|null $account
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render(string $template, array $context, ?Account $account)
    {
        if ($account != null) {
            $context['account_id'] = $account->getAccountId();
            $context['account_type'] = (string)$account->getAccountType();
            if ($account instanceof MedicalCenter) {
                $context['verified'] = $account->getVerificationState();
            }
        } else {
            $context['account_id'] = null;
            $context['account_type'] = null;
        }

        $context['site'] = "http://$_SERVER[HTTP_HOST]";
        $context['current_page'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $context['error'] = $this->httpHandler()->getParameter('error');
        $rendered = $this->getRenderer()->render($template, $context);
        $this->httpHandler()->setContent($rendered);
    }

    /**
     * @param string $className
     * @param string $page
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function loadOnlyIfUserIsOfType(string $className, string $page)
    {
        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof $className) {
            $this->render($page, array(), $currentAccount);
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }

    /**
     * @return Twig_Environment rendering engine
     */
    protected function getRenderer(): Twig_Environment
    {
        return $this->renderer;
    }

    /**
     * @return Account|null
     */
    protected function getCurrentAccount(): ?Account
    {
        try {
            $session = LoginService::continueSession();
            if ($session != null) {
                return $session->getSessionAccount();
            } else {
                return null;
            }
        } catch (AccountNotExistException $e) {
            LoginService::signOutSession();
            return null;
        } catch (InvalidDataException $e) {
            LoginService::signOutSession();
            return null;
        } catch (AccountRejectedException $e) {
            LoginService::signOutSession();
            return null;
        }
    }
}