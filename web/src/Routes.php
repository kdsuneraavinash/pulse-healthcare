<?php declare(strict_types=1);

namespace Pulse;

function getRoutes()
{
    return [
        // ['METHOD', '/path, ['Pulse\Controllers\Controller', 'method']]
        ['GET', '/test', ['Pulse\Controllers\Test', 'show']]
    ];
}

function getRouterErrorHandlers()
{
    return [
        [404, 'Error 404']
    ];
}

function getRouterDefaultErrorHandler(int $code)
{
    return 'Error ' . $code . ' Happened';
}