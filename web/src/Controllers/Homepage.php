<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\BaseController;

class Homepage extends BaseController
{
    public function __construct($req, $res, $rend)
    {
        parent::__construct($req, $res, $rend);
    }

    public function show()
    {
        $data = [
            'name' => $this->request->get('name', 'stranger'),
        ];
        $this->render('HomePage', $data);
    }
}
