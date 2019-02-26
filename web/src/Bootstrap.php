<?php declare(strict_types=1);

namespace Pulse;

/// Autoloader PSR-4

require __DIR__ . '/../vendor/autoload.php';

use Klein;
use Pulse\Components;
use Whoops;

/**
 * ========================================================
 * = BEFORE
 * ========================================================
 */

/// Initialize Http Handler
Components\HttpHandler::init($_GET, $_POST);

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
        /// Log error in production
        Components\Logger::log($e->getMessage(), Components\Logger::ERROR, $e->getFile());
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

Components\Database::init();


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

Components\TwigHandler::init();


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

$klein = new Klein\Klein();

/// Get routes has 2D arrays where each row is a route
/// and first = TYPE, second = /path, third = function response()
$routes = Components\Routes::getRoutes();
foreach ($routes as $route) {
    // Get type (POST/GET) and path (/login)
    $type = $route[0];
    $route_path = $route[1];

    // Get controller class method reference
    $controller = new $route[2][0]();
    $method = $route[2][1];
    $callback = [$controller, $method];

    // Pass reference to Klein
    $klein->respond($type, $route_path, $callback);
}

/// getRouterErrorHandlers() has dictionary like array where each key is a handler
///
/// getRouterDefaultErrorHandler($code) will have the
/// default response (Unhandled error)
$klein->onHttpError(function (int $code) {
    $router_err_handlers = Components\Routes::getRouterErrorHandlers();
    // If handler is supported
    if (array_key_exists($code, $router_err_handlers)) {
        Components\HttpHandler::getInstance()->redirect("http://$_SERVER[HTTP_HOST]/$code");
    } else {
        // Default handler
        Components\HttpHandler::getInstance()->redirect("http://$_SERVER[HTTP_HOST]/undefined?code=$code");
    }
});

$klein->dispatch();


/**
 * ========================================================
 * = AFTER
 * ========================================================
 */

// Echo result of page
Components\HttpHandler::getInstance()->echoContent();