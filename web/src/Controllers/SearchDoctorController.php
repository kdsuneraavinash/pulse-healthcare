<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components\Logger;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;

class SearchDoctorController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $page = $this->httpHandler()->postParameter('page');
        $account = $this->getCurrentAccount();
        $this->render('iframe/SearchDoctor.html.twig', array(), $account);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function post()
    {
        $name = $this->httpHandler()->postParameter('account');
        $slmc_id = $this->httpHandler()->postParameter('slmc_id');
        $category = $this->httpHandler()->postParameter('doctor-category');

        if ($category == 'NONE'){
            $category = null;
        }

        $results = DoctorDetails::searchDoctor($slmc_id, $name, $category);

        if ($results == null){
            // Nothing entered
            $error = "Empty Fields";
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/search/doctor?error=$error");
        } else if (sizeof($results) == 0) {
            // Empty results set
            $error = "No results found";
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/search/doctor?error=$error");
        } else {
            $this->render("SearchResults.html.twig", array('ret' => $results, 'size' => sizeof($results)),
                $this->getCurrentAccount());
        }
    }
}
