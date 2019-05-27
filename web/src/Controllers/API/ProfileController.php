<?php declare(strict_types=1);

namespace Pulse\Controllers\API;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\Patient\Patient;

class ProfileController extends APIController
{
    /**
     * @param Account $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function profile(?Account $currentAccount)
    {
        $jsonTemplate = 'api/Profile.json.twig';

        if ($currentAccount instanceof Patient) {
            $context = array();
            $context['id'] = $currentAccount->getAccountId();
            $context['name'] = $currentAccount->getPatientDetails()->getName();
            $context['nic'] = $currentAccount->getPatientDetails()->getNic();
            $context['email'] = $currentAccount->getPatientDetails()->getEmail();
            $context['phone_number'] = $currentAccount->getPatientDetails()->getPhoneNumber();
            $context['address'] = $currentAccount->getPatientDetails()->getAddress();
            $context['postal_code'] = $currentAccount->getPatientDetails()->getPostalCode();
            $this->render($jsonTemplate,
                array('message' => "Account Details Loaded", 'ok' => 'true', 'context' => $context),
                $currentAccount);
        } else if ($currentAccount == null) {
            $this->echoError($jsonTemplate,"User not logged in");
            return;
        } else {
            $this->echoError($jsonTemplate,"Current account is not a Patient");
            return;
        }
    }
}
