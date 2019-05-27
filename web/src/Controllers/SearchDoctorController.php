<?php declare(strict_types=1);

namespace Pulse\Controllers;


use Pulse\Components\Logger;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\Search\DoctorCategoryContext;
use Pulse\Models\Search\DoctorNoCategoryContext;
use Pulse\Models\Search\SearchContext;

class SearchDoctorController extends BaseController
{
    /**
     * @param Account $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getIFrame(Account $currentAccount)
    {
        $this->renderWithNoContext('iframe/SearchDoctor.twig', $currentAccount);
    }

    /**
     */
    public function getSearchResults()
    {
        $name = $this->httpHandler()->postParameter('full_name');
        $slmcId = $this->httpHandler()->postParameter('slmc_id');
        $category = $this->httpHandler()->postParameter('doctor_category');

        if ($category == 'NONE') {
            $category = null;
        }

        /**
         * Creates a searchContext according to the parameters passed by the user
         * and call the Static search method of the SearchContext class by passing
         *created searchContext object.
         */
        $slmcId = ($slmcId != null) ? $slmcId : null;
        $name = ($name != null) ? $name : null;

        if ($category) {
            $searchContext = new DoctorCategoryContext($slmcId, $name, $category);
        } else {
            $searchContext = new DoctorNoCategoryContext($slmcId, $name);
        }

        return SearchContext::search($searchContext);
    }

    /**
     * @param Account $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function postIframe(Account $currentAccount)
    {
        $results = $this->getSearchResults();

        if ($results == null || sizeof($results) == 0) {
            // Empty results set
            $error = "No results found";
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/{$currentAccount->getAccountType()}/search/doctor?error=$error");
        } else {
            $this->render("iframe/DoctorSearchResults.twig", array('ret' => $results, 'size' => sizeof($results)), $currentAccount);
        }
    }
}
