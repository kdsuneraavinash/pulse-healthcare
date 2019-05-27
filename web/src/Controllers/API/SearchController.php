<?php declare(strict_types=1);

namespace Pulse\Controllers\API;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Search\DoctorNoCategoryContext;
use Pulse\Models\Search\SearchContext;

class SearchController extends APIController
{
    /**
     * @param Account $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function search(?Account $currentAccount)
    {
        $jsonTemplate = 'api/Search.json.twig';
        if ($currentAccount instanceof Patient) {
            $name = $this->httpHandler()->getParameter('name');
            if ($name == null){
                $this->echoError($jsonTemplate, "No search term entered");
                return;
            }

            $searchContext = new DoctorNoCategoryContext(null, $name);
            $results =  SearchContext::search($searchContext);

            if ($results == null || sizeof($results) == 0) {
                $this->echoError($jsonTemplate,  "No results found");
            } else {
                $this->render($jsonTemplate, array('ret' => $results, 'size' => sizeof($results)), $currentAccount);
            }
        } else {
            $this->echoError($jsonTemplate, "Current account is not a Patient");
        }
    }
}
