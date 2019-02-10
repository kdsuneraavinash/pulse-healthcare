<?php declare(strict_types=1);

namespace Pulse;

/// Autoloader PSR-4

require __DIR__ . '/../vendor/autoload.php';

use Whoops;
use Http;
use DB;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Klein;

define('TEMPLATES', __DIR__ . '/../templates');
define('CACHE', __DIR__ . '/../cache');

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
/// = HTTP Initialization
/// ========================================================
/// HTTP Component Handler
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://github.com/PatrickLouys/http
/// ========================================================

// TODO: Checkout other HTTP Component Handlers and determine the
// most lightweight and easy to use library

$httpRequest = new Http\HttpRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
$httpResponse = new Http\HttpResponse;


/// ========================================================
/// = MeekroDB Initialization
/// ========================================================
/// Database Handler
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://meekro.com/docs.php
/// ========================================================

DB::$user = 'pulse_root';
DB::$password = 'password';
DB::$dbName = 'pulse';
DB::$host = 'localhost';
DB::$port = '3306';
DB::$encoding = 'latin1';


/// ========================================================
/// = Twig Initialization
/// ========================================================
/// Template Engine
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://twig.sensiolabs.org/
/// ========================================================

$loader = new Twig_Loader_Filesystem(TEMPLATES);
$twig = new Twig_Environment($loader, [
//    'cache' => __DIR__ . '/../cache',
]);


/// ========================================================
/// = Klein.php
/// ========================================================
/// Router
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://github.com/klein/klein.php
/// ========================================================

require __DIR__ . '/Routes.php';
require __DIR__ . '/BaseController.php';

$klein = new Klein\Klein();

/// Get routes has 2D arrays where each row is a route
/// and first = TYPE, second = /path, third = function response()
$routes = getRoutes();
foreach ($routes as $route) {
    $type = $route[0];
    $route_path = $route[1];

    $controller = new $route[2][0]();
    BaseController::activate($controller, $httpRequest, $httpResponse, $twig);
    $method = $route[2][1];
    $callback = [$controller, $method];
    $klein->respond($type, $route_path, $callback);
}

/// getRouterErrorHandlers() has 2D arrays where each row is a handler
/// and first = ERROR_CODE, second = response body
///
/// getRouterDefaultErrorHandler($code) will have the
/// default response (Unhandles error)
$klein->onHttpError(function (int $code, Klein\Klein $router) {
    $router_err_handlers = getRouterErrorHandlers();
    foreach ($router_err_handlers as $handler) {
        if ($code == $handler[0]) {
            $router->response()->body(generateErrorPage($handler[1]));
            return;
        }
    }
    $router->response()->body(generateErrorPage('other') . $code);
});


$klein->dispatch();


/// ========================================================
/// = HTTP sending response
/// ========================================================

foreach ($httpResponse->getHeaders() as $header) {
    header($header, false);
}

echo $httpResponse->getContent();