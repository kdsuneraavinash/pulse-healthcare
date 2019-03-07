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
        $region = $this->httpHandler()->postParameter('region');
        $doctor_details = 'doctor_details';

        $searchText = '+'.$slmc_id ." ". '+'.$account;
        $result = Database::search($doctor_details,$slmc_id,$account,$searchText,
            array("doctor_details"=>$doctor_details,"slmc_id"=>$slmc_id,"display_name"=>$account,(string)($searchText)=>$searchText));
        if($result){
            print_r($result);
        }


        if($result==null){
            $searchText = $slmc_id ." ". $account;
            $result = Database::search($doctor_details,$slmc_id,$account,$searchText,
                array("doctor_details"=>$doctor_details,"slmc_id"=>$slmc_id,"display_name"=>$account,(string)($searchText)=>$searchText));
            print_r($result);

        }


        Logger::log($account . ' ' . $slmc_id . ' ' . $region);
    }
}
