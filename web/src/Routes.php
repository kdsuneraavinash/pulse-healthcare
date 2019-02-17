<?php declare(strict_types=1);

namespace Pulse;

function getRoutes()
{
    return [
        // ['METHOD', '/path, ['Pulse\Controllers\Controller', 'method']]
        ['GET', '/', ['Pulse\Controllers\HomePageController', 'get']],
        ['GET', '/profile', ['Pulse\Controllers\ProfilePageController', 'get']],

        ['GET', '/test', ['Pulse\Controllers\Test\TestController', 'show']],
        ['POST', '/test', ['Pulse\Controllers\Test\TestController', 'show']],

        ['GET', '/login', ['Pulse\Controllers\LoginController', 'get']],
        ['POST', '/login', ['Pulse\Controllers\LoginController', 'post']],

        ['GET', '/register/medi', ['Pulse\Controllers\MedicalCenterRegistrationController', 'get']],
        ['POST', '/register/medi', ['Pulse\Controllers\MedicalCenterRegistrationController', 'post']],

        ['POST', '/logout', ['Pulse\Controllers\LogoutController', 'post']],
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