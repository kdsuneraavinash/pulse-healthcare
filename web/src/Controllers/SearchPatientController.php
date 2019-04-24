<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components\Logger;
use Pulse\Models\Admin\Admin;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Patient\PatientDetails;

class SearchPatientController extends BaseController
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
            $this->render('iframe/SearchPatient.html.twig', array(), $account);
        }else{
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }

    /**
     */
    public function getSearchResults()
    {
        $name = $this->httpHandler()->postParameter('name');
        $nic = $this->httpHandler()->postParameter('nic');
        $address = $this->httpHandler()->postParameter('address');

        return PatientDetails::searchPatient($name, $nic, $address);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function postIframe(){
        $account = $this->getCurrentAccount();
        if ($account == null || $account instanceof Patient){
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }

        $results = $this->getSearchResults();

        if ($results == null || sizeof($results) == 0) {
            // Empty results set
            $error = "No results found";
            Logger::log("http://$_SERVER[HTTP_HOST]/control/{$account->getAccountType()}/search/patient?error=$error");
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/{$account->getAccountType()}/search/patient?error=$error");
        } else {
            $this->render("iframe/PatientSearchResults.html.twig", array('ret' => $results, 'size' => sizeof($results)),
                $this->getCurrentAccount());
        }
    }
}
