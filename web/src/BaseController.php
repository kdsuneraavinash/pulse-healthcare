<?php declare(strict_types=1);

namespace Pulse;

use Http;
use Mustache_Engine;

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
     * @param Mustache_Engine $rederer HTML Rendering Object
     */
    public static function activate(BaseController $controller,
                                    Http\HttpRequest $httpRequest,
                                    Http\HttpResponse $httpResponse,
                                    Mustache_Engine $rederer)
    {
        $controller->response = $httpResponse;
        $controller->request = $httpRequest;
        $controller->rederer = $rederer;
    }

    /**
     * @return Http\HttpResponse response object
     */
    protected function getResponse(): Http\HttpResponse
    {
        return $this->response;
    }

    /**
     * @return Http\HttpRequest request object
     */
    protected function getRequest(): Http\HttpRequest
    {
        return $this->request;
    }

    /**
     * @return Mustache_Engine rendering engine
     */
    protected function getRenderer(): Mustache_Engine
    {
        return $this->rederer;
    }

    /**
     * @param string $template Template file name (without extension)
     * @param array $context Values to pass into file
     */
    protected function render(string $template, array $context)
    {
        $rendered = $this->getRenderer()->render($template, $context);
        $this->getResponse()->setContent($rendered);
    }
}