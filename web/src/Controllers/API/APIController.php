<?php declare(strict_types=1);

namespace Pulse\Controllers\API;

use Pulse\Controllers\BaseController;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Exceptions;
use Pulse\Models\Patient\Patient;

abstract class APIController extends BaseController
{

    /**
     * @param string $template
     * @param string $message
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function echoError(string $template, string $message)
    {
        $this->render($template,
            array('message' => $message, 'ok' => 'false'),
            null);
    }
}
