<?php declare(strict_types=1);

namespace Pulse\Controllers\API;

use Pulse\Controllers\BaseController;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Exceptions;
use Pulse\Models\Patient\Patient;

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
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function profile()
    {
        $account = $this->getCurrentAccount();
        if ($account instanceof Patient) {
            $context = array();
            $context['id'] = $account->getAccountId();
            $context['name'] = $account->getPatientDetails()->getName();
            $context['nic'] = $account->getPatientDetails()->getNic();
            $context['email'] = $account->getPatientDetails()->getEmail();
            $context['phone_number'] = $account->getPatientDetails()->getPhoneNumber();
            $context['address'] = $account->getPatientDetails()->getAddress();
            $context['postal_code'] = $account->getPatientDetails()->getPostalCode();
            $this->render('api/Profile.json.twig',
                array('message' => "Account Details Loaded", 'ok' => 'true', 'context' => $context),
                $account);
        } else {
            $this->echoError('Current account is not a patient');
            return;
        }
    }
}