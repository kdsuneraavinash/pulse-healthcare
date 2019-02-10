<?php declare(strict_types=1);

namespace Pulse;

function getRoutes()
{
    return [
        // ['METHOD', '/path, ['Pulse\Controllers\Controller', 'method']]
        ['GET', '/test', ['Pulse\Controllers\Test\TestController', 'show']],
        ['POST', '/test', ['Pulse\Controllers\Test\TestController', 'show']],
        ['GET', '/test/login', ['Pulse\Controllers\Test\LoginController', 'show']],
        ['POST', '/test/login', ['Pulse\Controllers\Test\LoginController', 'show']],
        ['POST', '/test/logout', ['Pulse\Controllers\Test\LogoutController', 'show']],
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