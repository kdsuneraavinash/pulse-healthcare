<?php declare(strict_types=1);

namespace Pulse;

/// Autoloader PSR-4

require __DIR__ . '/../vendor/autoload.php';

use Klein;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Whoops;

/**
 * ========================================================
 * = BEFORE
 * ========================================================
 */

include "Definitions.php";
HttpHandler::init($_GET, $_POST);

/**
 * ========================================================
 * = Whoops Initialization
 * ========================================================
 * Error Reporter
 * --------------------------------------------------------
 * DOCUMENTATION
 * https://github.com/filp/whoops
 * ========================================================
 */

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

/**
 * ========================================================
 * = MeekroDB Initialization
 * ========================================================
 * Database Handler
 * --------------------------------------------------------
 * DOCUMENTATION
 * https://meekro.com/docs.php
 * ========================================================
 */

Database::init();


/**
 * ========================================================
 * = Twig Initialization
 * ========================================================
 * Template Engine
 * --------------------------------------------------------
 * DOCUMENTATION
 * https://twig.sensiolabs.org/
 * ========================================================
 */

$loader = new Twig_Loader_Filesystem(Definitions::TEMPLATES);
$twig = new Twig_Environment($loader, [
    // TODO: Uncomment to cache and speedup process of templating
    //    'cache' => __DIR__ . '/../cache',
]);

/**
 * ========================================================
 * = Klein.php
 * ========================================================
 * Router
 * --------------------------------------------------------
 * DOCUMENTATION
 * https://github.com/klein/klein.php
 * ========================================================
 */

require __DIR__ . '/Routes.php';
require __DIR__ . '/Controllers/BaseController.php';

$klein = new Klein\Klein();

/// Get routes has 2D arrays where each row is a route
/// and first = TYPE, second = /path, third = function response()
$routes = getRoutes();
foreach ($routes as $route) {
    $type = $route[0];
    $route_path = $route[1];

    $controller = new $route[2][0]();
    Controllers\BaseController::activate($controller, $twig);
    $method = $route[2][1];
    $callback = [$controller, $method];
    $klein->respond($type, $route_path, $callback);
}

/// getRouterErrorHandlers() has 2D arrays where each row is a handler
/// and first = ERROR_CODE, second = response body
///
/// getRouterDefaultErrorHandler($code) will have the
/// default response (Unhandled error)
$klein->onHttpError(function (int $code) {
    $router_err_handlers = getRouterErrorHandlers();
    foreach ($router_err_handlers as $handler) {
        if ($code == $handler[0]) {
            header("Location: http://$_SERVER[HTTP_HOST]/$code");
            exit;
        }
    }
    header("Location: http://$_SERVER[HTTP_HOST]/undefined?code=$code");
    exit;
});

$klein->dispatch();

/**
 * ========================================================
 * = AFTER
 * ========================================================
 */

HttpHandler::getInstance()->echoContent();