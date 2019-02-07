<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\BaseController;
use DB;

class Test extends BaseController
{
    public function show()
    {
        $get_string = $this->getRequest()->getParameter('name', 'stranger');

        $db_query = DB::query("SELECT * FROM test");

        $data = [
            'name' => $get_string,
            'db' =>  $db_query,
        ];
        $this->render('Test', $data);
    }
}
