<?php declare(strict_types=1);

namespace Pulse\Controllers;


use Pulse\Components\Logger;
use Pulse\Models\Admin\Admin;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Search\DoctorCategoryContext;
use Pulse\Models\Search\DoctorNoCategoryContext;
//use Pulse\Models\Search\
use Pulse\Models\Search\SearchContext;

class SearchDoctorController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getIFrame()
    {
        $account = $this->getCurrentAccount();
        if ($account instanceof Doctor || $account instanceof Admin || $account instanceof MedicalCenter){
            $this->render('iframe/SearchDoctor.html.twig', array(), $account);
        }else{
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }

    /**
     */
    public function getSearchResults()
    {
        $name = $this->httpHandler()->postParameter('full_name');
        $slmc_id = $this->httpHandler()->postParameter('slmc_id');
        $category = $this->httpHandler()->postParameter('doctor_category');

        echo "<script>console.log($name)</script>";

        if ($category == 'NONE') {
            $category = null;
        }

        /**
         * Creates a searchContext according to the parameters passed by the user and call the Static search method of the SearchContext class by passing
         *created searchContext object.         
         */
        if($category){
            $searchContext = new DoctorCategoryContext(($slmc_id!=null) ? $slmc_id : null,($name!=null) ? $name :null,$category);

        }else{
            $searchContext = new DoctorNoCategoryContext(($slmc_id!=null) ? $slmc_id : null,($name!=null) ? $name:null);

        }

        return SearchContext::search($searchContext);

        //return DoctorDetails::searchDoctor($slmc_id, $name, $category);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function postIframe(){
        $account = $this->getCurrentAccount();
        if ($account == null){
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }

        $results = $this->getSearchResults();

        if ($results == null || sizeof($results) == 0) {
            // Empty results set
            $error = "No results found";
            Logger::log("http://$_SERVER[HTTP_HOST]/control/{$account->getAccountType()}/SearchContext/doctor?error=$error");
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/{$account->getAccountType()}/SearchContext/doctor?error=$error");
        } else {
            $this->render("iframe/DoctorSearchResults.html.twig", array('ret' => $results, 'size' => sizeof($results)),
                $this->getCurrentAccount());
        }
    }
}
