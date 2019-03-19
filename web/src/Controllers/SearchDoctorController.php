<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components\Logger;
use Pulse\Components\Utils;
use Pulse\Models\MedicalCenter\MedicalCenter;

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
        // $region = $this->httpHandler()->postParameter('region');

        $category = $this->httpHandler()->postParameter('doctor-category');

        $results = MedicalCenter::searchDoctor($slmc_id, $name, $category);

        if ($results == null || sizeof($results) == 0) {
            // Empty results set
            $error = "No results found";
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/search/doctor?error=$error");
        } else {
            $this->render("SearchResults.html.twig", array('ret' => $results, 'size' => sizeof($results)),
                $this->getCurrentAccount());
        }
    }
}
