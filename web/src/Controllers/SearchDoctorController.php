<?php declare(strict_types=1);

namespace Pulse\Controllers;


//use DB;
use Pulse\Components\Database;
use Pulse\Components\Logger;

class SearchDoctorController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show()
    {
        $get_string = $this->httpHandler()->getParameter('key');
        $post_string = $this->httpHandler()->getParameter('key');
        $db_query = Database::query("SELECT * FROM accounts;", array());
        $db_session_query = Database::query("SELECT * FROM sessions;", array());

        $data = [
            'get' => $get_string,
            'post' => $post_string,
            'db' => $db_query,
            'site' => "http://$_SERVER[HTTP_HOST]",
            'db_session' => $db_session_query
        ];
        $this->render('SearchDoctor.html.twig', $data, null);
    }

    public function get()
    {
    }





    public function post(){

        $account = $this->httpHandler()->postParameter('account');
        $slmc_id = $this->httpHandler()->postParameter('slmc_id');
        $email = $this->httpHandler()->postParameter('email');
        $nic = $this->httpHandler()->postParameter('nic');
        $region = $this->httpHandler()->postParameter('region');

        //StaticLogger::loggerInfo($account . ' ' . $slmc_id . ' ' .$email. ' ' . $nic . ' ' . $region);

        //////////////////////////////////////////////

        if($slmc_id!==null){

            $query = Database::query('SELECT * FROM doctor_details WHERE slmc_id = :slmc_id',
                array("slmc_id" => $slmc_id));

            Logger::log(join(", ", $query));
        }








































        Logger::log($account . ' ' . $slmc_id . ' ' . $email . ' ' . $nic . ' ' . $region);
    }
}
