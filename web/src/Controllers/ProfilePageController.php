<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\AccountFactory;
use Pulse\Models\Admin\Admin;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Exceptions\AccountNotExistException;
use Pulse\Models\Exceptions\AccountRejectedException;
use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Patient\Patient;

class ProfilePageController extends BaseController
{
    /**
     * @param Account $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get(Account $currentAccount)
    {
            $context = $this->populate_with_account($currentAccount);
            $context['is_viewing'] = false;
            $this->render("ProfilePage.html.twig", $context, $currentAccount);
    }

    /**
     * @param Account $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getShowProfile(Account $currentAccount)
    {
        $accountId = $this->httpHandler()->getParameter("user");

        if ($accountId == null) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/404");
            exit();
        }
        try {
            $accountFactory = new AccountFactory();
            $account = $accountFactory->getAccount($accountId, true);
        } catch (AccountNotExistException|AccountRejectedException|InvalidDataException $e) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/404");
            exit();
        }

        $context = $this->populate_with_account($account);
        $context['is_viewing'] = true;
        $this->render("ProfilePage.html.twig", $context, $currentAccount);
    }

    private function populate_with_account(Account $account): array
    {
        $context = array();
        $context['profile_account_id'] = $account->getAccountId();
        $context['profile_type'] = $account->getAccountType();
        if ($account instanceof Patient) {
            $context['name'] = $account->getPatientDetails()->getName();
            $context['nic'] = $account->getPatientDetails()->getNic();
            $context['email'] = $account->getPatientDetails()->getEmail();
            $context['phone_number'] = $account->getPatientDetails()->getPhoneNumber();
            $context['address'] = $account->getPatientDetails()->getAddress();
            $context['postal_code'] = $account->getPatientDetails()->getPostalCode();
        } else if ($account instanceof MedicalCenter) {
            $context['name'] = $account->getMedicalCenterDetails()->getName();
            $context['phsrc'] = $account->getMedicalCenterDetails()->getPhsrc();
            $context['email'] = $account->getMedicalCenterDetails()->getEmail();
            $context['fax'] = $account->getMedicalCenterDetails()->getFax();
            $context['phone_number'] = $account->getMedicalCenterDetails()->getPhoneNumber();
            $context['address'] = $account->getMedicalCenterDetails()->getAddress();
            $context['postal_code'] = $account->getMedicalCenterDetails()->getPostalCode();
        } else if ($account instanceof Doctor) {
            $context['name'] = $account->getDoctorDetails()->getDisplayName();
            $context['full_name'] = $account->getDoctorDetails()->getFullName();
            $context['slmc_id'] = $account->getDoctorDetails()->getSlmcId();
            $context['nic'] = $account->getDoctorDetails()->getNic();
            $context['category'] = $account->getDoctorDetails()->getCategory();
            $context['email'] = $account->getDoctorDetails()->getEmail();
            $context['phone_number'] = $account->getDoctorDetails()->getPhoneNumber();
            $context['creation_date'] = $account->getDoctorDetails()->getCreationDate();
            $context['last_login'] = $account->getDoctorDetails()->getLastLoginDate();
        } else if ($account instanceof Admin) {
            $context['name'] = "Administrator";
        }
        return $context;
    }
}