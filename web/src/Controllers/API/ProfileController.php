<?php declare(strict_types=1);

namespace Pulse\Controllers\API;

use Pulse\Controllers\BaseController;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Exceptions;
use Pulse\Models\Patient\Patient;
use Pulse\Components\Definitions;

class ProfileController extends BaseController
{
    /**
     * @param string $message
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function echoError(string $message)
    {
        $this->render('api/Profile.json.twig',
            array('message' => $message, 'ok' => 'false'),
            null);
    }


    /**
     * @param Account $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function profile(?Account $currentAccount)
    {
        if ($currentAccount instanceof Patient) {
            $context = array();
            $context['id'] = $currentAccount->getAccountId();
            $context['name'] = $currentAccount->getPatientDetails()->getName();
            $context['nic'] = $currentAccount->getPatientDetails()->getNic();
            $context['email'] = $currentAccount->getPatientDetails()->getEmail();
            $context['phone_number'] = $currentAccount->getPatientDetails()->getPhoneNumber();
            $context['address'] = $currentAccount->getPatientDetails()->getAddress();
            $context['postal_code'] = $currentAccount->getPatientDetails()->getPostalCode();
            $this->render('api/Profile.json.twig',
                array('message' => "Account Details Loaded", 'ok' => 'true', 'context' => $context),
                $currentAccount);
        } else if ($currentAccount == null) {
            $this->echoError("User not logged in");
            return;
        } else {
            $this->echoError("Current account is not a Patient");
            return;
        }
    }
}
