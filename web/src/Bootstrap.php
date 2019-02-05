<?php declare(strict_types=1);

namespace Pulse;

/// Autoloader PSR-4
require __DIR__ . '/../vendor/autoload.php';

/// ========================================================
/// = Whoops Initialization
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

// Test whoops
// throw new \Exception;
