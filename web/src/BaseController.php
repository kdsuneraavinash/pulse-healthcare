<?php declare(strict_types=1);

namespace Pulse;

use Symfony\Component\HttpFoundation;
use Mustache_Engine;

abstract class BaseController
{
    protected $response;
    protected $request;
    protected $rederer;

    protected function __construct(HttpFoundation\Request $httpRequest,
                                HttpFoundation\Response $httpResponse,
                                Mustache_Engine $rederer)
    {
        $this->response = $httpResponse;
        $this->request = $httpRequest;
        $this->rederer = $rederer;
    }

    protected function render(string $template, array $context){
        $rendered = $this->rederer->render($template, $context);
        $this->response->setContent($rendered);
    }
}