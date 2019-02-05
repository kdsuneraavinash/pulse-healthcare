<?php declare(strict_types=1);

namespace Pulse;

/// Autoloader PSR-4

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation;


/// ========================================================
/// = Whoops Initialization
/// ========================================================
/// Error Reporter
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://github.com/filp/whoops
/// ========================================================

error_reporting(E_ALL);

$environment = 'development';

/// Register the error handler
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function (\Exception $e) {
        // TODO: Friendly error page and send an email to the developer
        echo 'Error Occurred in Production Mode: ' . $e->getCode();
    });
}

$whoops->register();


/// ========================================================
/// = HTTP Foundation Initialization
/// ========================================================
/// HTTP Component Handler
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://symfony.com/doc/current/components/http_foundation.html
/// ========================================================

$httpRequest = HttpFoundation\Request::createFromGlobals();
$httpResponse = new HttpFoundation\Response();

$httpResponse->setContent("<h1>Hello world again!</h1>");
$httpResponse->setStatusCode(200);

$httpResponse->send();