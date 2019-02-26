<?php declare(strict_types=1);

namespace Pulse;


class HttpHandler{
    private $getParameters;
    private $postParameters;
    private $content;

    private static $instance;

    /**
     * @param array $get
     * @param array $post
     */
    public static function init(array $get, array $post){
        if (self::$instance == null){
            self::$instance = new HttpHandler($get, $post);
        }
    }

    /**
     * @return mixed
     */
    public static function getInstance(){
        return self::$instance;
    }

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
     * @param string $key
     * @param string|null $defaultValue
     * @return string|null
     */
    public function getQueryParameter(string $key, ?string $defaultValue = null){
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
    public function getBodyParameter(string $key, ?string $defaultValue = null){
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
    public function getParameter(string $key, ?string $defaultValue = null){
        if (array_key_exists($key, $this->postParameters)) {
            return $this->postParameters[$key];
        }
        if (array_key_exists($key, $this->getParameters)) {
            return $this->getParameters[$key];
        }
        return $defaultValue;
    }

    public function setContent(string $content){
        $this->content = $content;
    }

    public function echoContent(){
        echo $this->content;
    }
}