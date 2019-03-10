<?php declare(strict_types=1);

namespace Pulse\Controllers;


//use DB;
use Pulse\Components\Database;
use Pulse\Components\Logger;
use Pulse\Components\Utils;
use Pulse\Models\Doctor\Doctor;

class SearchDoctorController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $account = $this->getCurrentAccount();
        $this->render('SearchDoctor.html.twig', array(), $account);
    }

    public function post(){

        $displayName = $this->httpHandler()->postParameter('display_name');
        $slmcId = $this->httpHandler()->postParameter('slmc_id');

        $result = Doctor::searchDoctor($slmcId, $displayName);

        Logger::log(Utils::array2string($result));
    }


}
