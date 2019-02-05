<?php declare(strict_types=1);

namespace Pulse;

/// Autoloader PSR-4

require __DIR__ . '/../vendor/autoload.php';

use Whoops;
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
$whoops = new Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function (\Exception $e) {
        // TODO: Log error or send an email to dev
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

//TODO: Checkout other HTTP Component Handlers and determine the
// most lightweight and easy to use library

$httpRequest = HttpFoundation\Request::createFromGlobals();
$httpResponse = new HttpFoundation\Response();


/// ========================================================
/// = Klein.php
/// ========================================================
/// Router
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://github.com/klein/klein.php
/// ========================================================

require __DIR__ . '/../src/Routes.php';


/// ========================================================
/// = HTTP Foundation sending response
/// ========================================================

$httpResponse->send();