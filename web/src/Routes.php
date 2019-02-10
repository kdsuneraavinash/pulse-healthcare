<?php declare(strict_types=1);

namespace Pulse;

function getRoutes()
{
    return [
        // ['METHOD', '/path, ['Pulse\Controllers\Controller', 'method']]
        ['GET', '/test', ['Pulse\Controllers\TestController', 'show']],
        ['POST', '/test', ['Pulse\Controllers\TestController', 'show']],
        ['GET', '/login', ['Pulse\Controllers\LoginController', 'show']],
        ['POST', '/login', ['Pulse\Controllers\LoginController', 'show']],
        ['POST', '/logout', ['Pulse\Controllers\LogoutController', 'show']],
    ];
}

function getRouterErrorHandlers()
{
    return [
        [404, '404']
    ];
}

function generateErrorPage(string $name)
{
    return file_get_contents('error/' . $name . '.html');
}