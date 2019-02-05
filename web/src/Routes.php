<?php declare(strict_types=1);

namespace Pulse;

use Klein;

$klein = new Klein\Klein();


/// ========================================================
/// Routes
/// ========================================================

$klein->respond('GET', '/hello-world', function () {
    return 'Hello World!';
});
$klein->respond('GET', '/another-route', function () {
    return 'Another Route!';
});


/// ========================================================
/// Handles errors that can occur because of routing
/// https://github.com/klein/klein.php/wiki/Handling-404's
/// ========================================================

$klein->onHttpError(function (int $code, Klein\Klein $router) {
    switch ($code) {
        case 404:
            $router->response()->body(
                'Error 404'
            );
            break;
        default:
            $router->response()->body(
                'Error ' . $code . ' Happened'
            );
    }
});


/// ========================================================
/// Dispatching Routes
/// ========================================================

$klein->dispatch();