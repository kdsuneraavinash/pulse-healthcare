<?php declare(strict_types=1);

namespace Pulse;

use Http;
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
     */
    protected function render(string $template, array $context)
    {
        try {
            $rendered = $this->getRenderer()->render($template, $context);
            $this->getResponse()->setContent($rendered);
        } catch (\Exception $e) {
            $this->getResponse()->setContent($e->getMessage());
        }
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
}