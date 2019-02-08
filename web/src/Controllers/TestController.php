<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\BaseController;
use DB;

class TestController extends BaseController
{
    public function show()
    {
        $get_string = $this->getRequest()->getParameter('name', 'stranger');
        $db_query = DB::query("SELECT TOP 1 1 FROM test WHERE ID = 170081;");

        $data = [
            'name' => $get_string,
            'db' => $db_query,
        ];
        $this->render('TestTemplate', $data);
    }
}
