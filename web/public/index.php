<?php declare(strict_types=1);

function databaseErrorHandler($param)
{
    echo str_replace('[DATABASE_ERROR]', $param['error'], file_get_contents("database_error.html"));
    die;
}

require __DIR__ . '/../src/Bootstrap.php';
