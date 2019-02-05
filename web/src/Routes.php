<?php declare(strict_types=1);

namespace Pulse;

function getRoutes()
{
    return [
        // ['METHOD', '/path, ['Pulse\Controllers\Controller', 'method']]
        ['GET', '/hello-world', ['Pulse\Controllers\Homepage', 'show']]
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