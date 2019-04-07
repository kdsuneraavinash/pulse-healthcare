<?php declare(strict_types=1);

namespace Pulse;

/// Autoloader PSR-4

require __DIR__ . '/../vendor/autoload.php';

use Pulse\Components;
use Whoops;

/**
 * ========================================================
 * = BEFORE
 * ========================================================
 */

error_reporting(E_ALL);
$environment = 'development';

/// Register the error handler
$whoops = new Whoops\Run;
if ($environment === 'development') {
    $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function (\Exception $e) {
        /// Log error in production
        Components\Logger::log($e->getMessage(), Components\Logger::ERROR, $e->getFile());
    });
}

$whoops->register();

/// Initialize Http Handler
Components\HttpHandler::init($_GET, $_POST);

/// Initialize Twig Handler
Components\TwigHandler::init();

/// Initialize Router
Components\Router::init();

/**
 * ========================================================
 * = AFTER
 * ========================================================
 */

// Echo result of page
Components\HttpHandler::getInstance()->echoContent();