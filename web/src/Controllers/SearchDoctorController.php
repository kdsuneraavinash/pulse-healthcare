<?php declare(strict_types=1);

namespace Pulse\Controllers;

use DB;
use Pulse\Controllers\BaseController;
use Pulse\StaticLogger;

class SearchDoctorController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show()
    {
        $get_string = $this->getRequest()->getQueryParameter('key');
        $post_string = $this->getRequest()->getBodyParameter('key');
        $db_query = DB::query("SELECT * FROM test;");
        $db_session_query = DB::query("SELECT * FROM sessions;");

        $data = [
            'get' => $get_string,
            'post' => $post_string,
            'db' => $db_query,
            'site' => "http://$_SERVER[HTTP_HOST]",
            'db_session' => $db_session_query
        ];
        $this->render('SearchDoctor.html.twig', $data, null);
    }

    public function get(){




    }


    public function post(){

        $account = $this->getRequest()->getBodyParameter('account');
        $slmc_id = $this->getRequest()->getBodyParameter('slmc_id');
        $email = $this->getRequest()->getBodyParameter('email');
        $nic = $this->getRequest()->getBodyParameter('nic');
        $region = $this->getRequest()->getBodyParameter('region');

        StaticLogger::loggerInfo($account . ' ' . $slmc_id . ' ' .$email. ' ' . $nic . ' ' . $region);




    }



}
