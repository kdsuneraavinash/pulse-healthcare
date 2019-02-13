<?php declare(strict_types=1);

namespace Pulse\Controllers;

use DB;
use Pulse\BaseController;
use Pulse\Models\LoginService;

class LogoutController extends BaseController
{
    public function show()
    {
        LoginService::signOutSession();
        header("Location: http://$_SERVER[HTTP_HOST]/test/login");
        exit();
    }
}