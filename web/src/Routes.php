<?php declare(strict_types=1);

namespace Pulse;

function getRoutes()
{
    return [
        [
            'GET', '/hello-world',
            function () {
                return 'Hello World!';
            }
        ],
        [
            'GET', '/another-route',
            function () {
                return 'Another Route!';
            }
        ]
    ];
}

function getRouterErrorHandlers()
{
    return [
        [
            404, 'Error 404'
        ]
    ];
}

function getRouterDefaultErrorHandler(int $code)
{
    return 'Error ' . $code . ' Happened';
}