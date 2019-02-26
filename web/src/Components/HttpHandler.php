<?php declare(strict_types=1);

namespace Pulse\Components;


class HttpHandler
{
    private static $instance;
    private $getParameters;
    private $postParameters;
    private $content;

    /**
     * HttpHandler constructor.
     * @param array $get
     * @param array $post
     */
    public function __construct(array $get, array $post)
    {
        $this->getParameters = $get;
        $this->postParameters = $post;
    }

    /**
     * @param array $get
     * @param array $post
     */
    public static function init(array $get, array $post)
    {
        if (self::$instance == null) {
            self::$instance = new HttpHandler($get, $post);
        }
    }

    /**
     * @return HttpHandler
     */
    public static function getInstance(): HttpHandler
    {
        return self::$instance;
    }

    /**
     * @param string $key
     * @param string|null $defaultValue
     * @return string|null
     */
    public function getParameter(string $key, ?string $defaultValue = null)
    {
        if (array_key_exists($key, $this->getParameters)) {
            return $this->getParameters[$key];
        }
        return $defaultValue;
    }

    /**
     * @param string $key
     * @param string|null $defaultValue
     * @return string|null
     */
    public function postParameter(string $key, ?string $defaultValue = null)
    {
        if (array_key_exists($key, $this->postParameters)) {
            return $this->postParameters[$key];
        }
        return $defaultValue;
    }

    /**
     * @param string $key
     * @param string|null $defaultValue
     * @return string|null
     */
    public function anyParameter(string $key, ?string $defaultValue = null)
    {
        if (array_key_exists($key, $this->postParameters)) {
            return $this->postParameters[$key];
        }
        if (array_key_exists($key, $this->getParameters)) {
            return $this->getParameters[$key];
        }
        return $defaultValue;
    }

    /**
     * @param string $url
     */
    public function redirect(string $url)
    {
        header("Location: $url");
        exit();
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function echoContent()
    {
        echo $this->content;
    }
}