<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\BaseController;

class Homepage extends BaseController
{
    public function __construct($req, $res)
    {
        parent::__construct($req, $res);
    }

    public function show()
    {
        $content = '<h1>Hello World</h1>';
        $content .= 'Hello ' . $this->request->get('name', 'stranger');
        $this->response->setContent($content);
    }
}
