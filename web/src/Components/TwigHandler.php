<?php declare(strict_types=1);

namespace Pulse\Components;


use Pulse\Definitions;
use Twig_Environment;
use Twig_Loader_Filesystem;

class TwigHandler
{
    private static $instance;

    /**
     */
    public static function init()
    {
        if (self::$instance == null) {
            $loader = new Twig_Loader_Filesystem(Definitions::TEMPLATES);
            self::$instance = new Twig_Environment($loader, [
                // TODO: Uncomment to cache and speedup process of templating
                //    'cache' => __DIR__ . '/../cache',
            ]);
        }
    }

    /**
     * @return Twig_Environment
     */
    public static function getInstance(): Twig_Environment
    {
        return self::$instance;
    }


}