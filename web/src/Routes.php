<?php declare(strict_types=1);

namespace Pulse;

use Twig_Environment;
use Twig_Loader_Filesystem;

function getRoutes()
{
    return [
        // ['METHOD', '/path, ['Pulse\Controllers\Controller', 'method']]
        ['GET', '/test', ['Pulse\Controllers\TestController', 'show']]
    ];
}

function getRouterErrorHandlers()
{
    return [
        [404, '404']
    ];
}

function generateErrorPage(string $name){
    return file_get_contents('error/'.$name.'.html');
}