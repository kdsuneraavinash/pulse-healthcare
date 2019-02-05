<?php declare(strict_types = 1);

namespace Pulse;

use Symfony\Component\HttpFoundation;

abstract class BaseController
{
    protected $response;
    protected $request;

    public function __construct(HttpFoundation\Request $httpRequest, HttpFoundation\Response $httpResponse)
    {
        $this->response = $httpResponse;
        $this->request = $httpRequest;
    }
}