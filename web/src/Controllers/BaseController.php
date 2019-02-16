<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Http;
use Pulse\Exceptions\UserNotExistException;
use Pulse\Models\LoginService;
use Twig_Environment;

abstract class BaseController
{
    private $response;
    private $request;
    private $rederer;

    /**
     * Activates a Controller with HTTP objects and renderer.
     * @param BaseController $controller Controller
     * @param Http\HttpRequest $httpRequest HTTP Request Object
     * @param Http\HttpResponse $httpResponse HTTP Response Object
     * @param Twig_Environment $rederer HTML Rendering Object
     */
    public static function activate(BaseController $controller,
                                    Http\HttpRequest $httpRequest,
                                    Http\HttpResponse $httpResponse,
                                    Twig_Environment $rederer)
    {
        $controller->response = $httpResponse;
        $controller->request = $httpRequest;
        $controller->rederer = $rederer;
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
     * @param string|null $userId
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render(string $template, array $context, ?string $userId)
    {
        $context['site'] = "http://$_SERVER[HTTP_HOST]";
        $context['user_id'] = $userId;
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
        return $this->rederer;
    }

    /**
     * @return Http\HttpResponse response object
     */
    protected function getResponse(): Http\HttpResponse
    {
        return $this->response;
    }

    /**
     * @return string|null |null
     */
    protected function getCurrentUserId() : ?string
    {
        try {
            $session = LoginService::continueSession();
            if ($session != null) {
                return $session->getSessionUserId();
            } else {
                return null;
            }
        } catch (UserNotExistException $e) {
            LoginService::signOutSession();
            return null;
        }
    }
}