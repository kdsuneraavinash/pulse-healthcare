<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\Search\PatientNICContext;
use Pulse\Models\Search\PatientNoNICContext;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Search\SearchContext;

class SearchPatientController extends BaseController
{
    /**
     * @param Account $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getIFrame(Account $currentAccount)
    {
        if ($currentAccount instanceof Patient) {
            $this->redirectToErrorPage(405);
        } else {
            $this->renderWithNoContext('iframe/SearchPatient.twig', $currentAccount);
        }
    }


    public function getSearchResults()
    {
        $name = $this->httpHandler()->postParameter('name');
        $nic = $this->httpHandler()->postParameter('nic');
        $address = $this->httpHandler()->postParameter('address');

        $name = ($name != null) ? $name : null;
        $address = ($address != null) ? $address : null;

        if ($nic) {
            $searchContext = new PatientNICContext($nic, $name, $address);
        } else {
            $searchContext = new PatientNoNICContext($name, $address);
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
        if ($currentAccount instanceof Patient) {
            $this->redirectToErrorPage(405);
        }

        $results = $this->getSearchResults();

        if ($results == null || sizeof($results) == 0) {
            // Empty results set
            $error = "No results found";
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/{$currentAccount->getAccountType()}/search/patient?error=$error");
        } else {
            $this->render("iframe/PatientSearchResults.twig", array('ret' => $results, 'size' => sizeof($results)), $currentAccount);
        }
    }
}
