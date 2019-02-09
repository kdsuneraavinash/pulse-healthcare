<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\MVC\BaseController;
use DB;

class TestController extends BaseController
{
    public function show()
    {
        $get_string = $this->getRequest()->getQueryParameter('key');
        $post_string = $this->getRequest()->getBodyParameter('key');
        $db_query = DB::query("SELECT * FROM test;");

        $data = [
            'get' => $get_string,
            'post' => $post_string,
            'db' => $db_query,
        ];
        $this->render('TestTemplate.html.twig', $data);
    }
}
