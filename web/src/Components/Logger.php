<?php

namespace Pulse\Components;

class Logger
{
    const INFO = 'INFO';
    const WARNING = 'WARNING';
    const ERROR = 'ERROR';
    const DEBUG = 'DEBUG';

    public static function log(string $message, string $type = 'DEBUG', string $module = 'main')
    {
        $today = date("F j, Y, g:i a");
        $log = "$today ---- Module[$module] ---- $type : $message" . PHP_EOL;

        try {
            file_put_contents(__DIR__ . '/../../log/' . date("j.n.Y") . '.log', $log, FILE_APPEND);
        } catch (\Exception $e) {
        }
    }
}