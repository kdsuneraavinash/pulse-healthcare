<?php declare(strict_types=1);

namespace Pulse;

/// Autoloader PSR-4

require __DIR__ . '/../vendor/autoload.php';

use Whoops;
use Symfony\Component\HttpFoundation;
use DB;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Klein;


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
/// = MeekroDB Initialization
/// ========================================================
/// Database Handler
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://meekro.com/docs.php
/// ========================================================

/*
 * SETUP MYSQL
 *
 * $ sudo mysql
 * > ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
 * $ mysql_secure_installation
 *
 * $ mysql -u root -p
 * > CREATE USER 'pulse_root'@'localhost' IDENTIFIED BY 'password';
 * > CREATE DATABASE pulse;
 * > GRANT ALL PRIVILEGES ON pulse . * TO 'pulse_root'@'localhost';
 * > CREATE TABLE test (
 *  ->     ID int NOT NULL,
 *  ->     LastName varchar(255) NOT NULL,
 *  ->     FirstName varchar(255),
 *  ->     Age int,
 *  ->     PRIMARY KEY (ID)
 *  -> );
 * > INSERT INTO test (ID, LastName, FirstName, Age ) VALUES (170081, "Chandrasiri", 'Sunera', 22 );
 * > SELECT * FROM test;
 * */
DB::$user = 'pulse_root';
DB::$password = 'password';
DB::$dbName = 'pulse';
DB::$host = 'localhost';
DB::$port = '3306';
DB::$encoding = 'latin1';


/// ========================================================
/// = Mustache Initialization
/// ========================================================
/// Template Engine
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://github.com/bobthecow/mustache.php
/// ========================================================

$mustache = new Mustache_Engine([
    'loader' => new Mustache_Loader_FilesystemLoader(
        dirname(__DIR__) . '/templates', [
        'extension' => '.html',
    ]),
]);


/// ========================================================
/// = Klein.php
/// ========================================================
/// Router
/// --------------------------------------------------------
/// DOCUMENTATION
/// https://github.com/klein/klein.php
/// ========================================================

require __DIR__ . '/../src/Routes.php';

$klein = new Klein\Klein();

/// Get routes has 2D arrays where each row is a route
/// and first = TYPE, second = /path, third = function response()
$routes = getRoutes();
foreach ($routes as $route) {
    $type = $route[0];
    $route_path = $route[1];

    $controller = new $route[2][0]($httpRequest, $httpResponse, $mustache);
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
            $router->response()->body($handler[1]);
            return;
        }
    }
    $router->response()->body(getRouterDefaultErrorHandler($code));
});

$klein->dispatch();


/// ========================================================
/// = HTTP Foundation sending response
/// ========================================================

$httpResponse->send();