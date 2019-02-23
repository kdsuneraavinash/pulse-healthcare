<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Http;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Twig_Environment;

abstract class BaseController
{
    private $response;
    private $request;
    private $renderer;

    /**
     * Activates a Controller with HTTP objects and renderer.
     * @param BaseController $controller Controller
     * @param Http\HttpRequest $httpRequest HTTP Request Object
     * @param Http\HttpResponse $httpResponse HTTP Response Object
     * @param Twig_Environment $renderer HTML Rendering Object
     */
    public static function activate(BaseController $controller,
                                    Http\HttpRequest $httpRequest,
                                    Http\HttpResponse $httpResponse,
                                    Twig_Environment $renderer)
    {
        $controller->response = $httpResponse;
        $controller->request = $httpRequest;
        $controller->renderer = $renderer;
    }

    /**
     * @return Http\HttpRequest request object
     */
    protected function getRequest(): Http\HttpRequest
    {
        return $this->request;
    }

    /**
     * @param string $template Template file name (without extension)
     * @param array $context Values to pass into file
     * @param string|null $accountId
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render(string $template, array $context, ?string $accountId)
    {
        $context['site'] = "http://$_SERVER[HTTP_HOST]";
        $context['account_id'] = $accountId;
        $context['current_page'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $context['error'] = $this->getRequest()->getQueryParameter('error');
        $rendered = $this->getRenderer()->render($template, $context);
        $this->getResponse()->setContent($rendered);
    }

    /**
     * @return Twig_Environment rendering engine
     */
    protected function getRenderer(): Twig_Environment
    {
        return $this->renderer;
    }

    /**
     * @return Http\HttpResponse response object
     */
    protected function getResponse(): Http\HttpResponse
    {
        return $this->response;
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
        }
    }

    /**
     * @return string|null |null
     */
    protected function getCurrentAccountId(): ?string
    {
        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount == null) {
            return null;
        }
        return $currentAccount->getAccountId();
    }
}