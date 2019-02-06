<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\BaseController;
use DB;

class Test extends BaseController
{
    public function __construct($req, $res, $rend)
    {
        parent::__construct($req, $res, $rend);
    }

    public function show()
    {
        $get_string = $this->request->get('name', 'stranger');

        $db_query = DB::query("SELECT * FROM test");

        $data = [
            'name' => $get_string,
            'db' =>  $db_query,
        ];
        $this->render('HomePage', $data);
    }
}
