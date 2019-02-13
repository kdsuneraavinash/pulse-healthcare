<?php declare(strict_types=1);

namespace Pulse\Controllers;

use DB;
use Pulse\BaseController;
use Pulse\Framework\Session;

class LogoutController extends BaseController
{
    public function show()
    {
        if (isset($_SESSION["SESSION_KEY"])){
            Session::closeSessionWithContext($_SESSION["SESSION_USER"], $_SESSION["SESSION_KEY"]);
            unset($_SESSION["SESSION_KEY"]);
            unset($_SESSION["SESSION_USER"]);
        }

        header("Location: http://$_SERVER[HTTP_HOST]/test/login");
        exit();
    }
}