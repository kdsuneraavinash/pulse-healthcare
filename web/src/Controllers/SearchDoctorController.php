<?php declare(strict_types=1);

namespace Pulse\Controllers;


//use DB;
use Pulse\Components\Database;
use Pulse\Components\Logger;
use Pulse\Models\MedicalCenter\MedicalCenter;

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
        //$doctor_details = 'doctor_details';
        $category =$this->httpHandler()->postParameter('doctor-category');

        $this->search($slmc_id,$account,$region,$category);



        //$this->render('SearchDoctor.html.twig',$result, null);

        Logger::log($account . ' ' . $slmc_id . ' ' . $region);
    }



    private function search($slmc_id,$account,$region,$category){
        if($account!=null){
            $name=explode(" ",$account);
        }else{
            $name = array();
        }


        $ret = MedicalCenter::searchDoctor($slmc_id,$name,$region,$category);

        if (! $ret){
            $error = "No results found";
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/search/doctor?error=$error");
        }

    }


}
