<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Exceptions\AccountNotExistException;
use Pulse\Models\Exceptions\AccountRejectedException;
use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Patient\Patient;

class ProfilePageController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $current_account = $this->getCurrentAccount();
        $context = array();
        $context['account_id'] = $this->getCurrentAccount()->getAccountId();
        $context['account_type'] = $this->getCurrentAccount()->getAccountType();
        if($current_account instanceof Patient){
            $context['name']=$current_account->getPatientDetails()->getName();
            $context['nic']=$current_account->getPatientDetails()->getNic();
            $context['email']=$current_account->getPatientDetails()->getEmail();
            $context['phone_number']=$current_account->getPatientDetails()->getPhoneNumber();
            $context['address']=$current_account->getPatientDetails()->getAddress();
            $context['postal_code']=$current_account->getPatientDetails()->getPostalCode();
        }else if($current_account instanceof MedicalCenter){
            $context['name']=$current_account->getMedicalCenterDetails()->getName();
            $context['phsrc']=$current_account->getMedicalCenterDetails()->getPhsrc();
            $context['email']=$current_account->getMedicalCenterDetails()->getEmail();
            $context['fax']=$current_account->getMedicalCenterDetails()->getFax();
            $context['phone_number']=$current_account->getMedicalCenterDetails()->getPhoneNumber();
            $context['address']=$current_account->getMedicalCenterDetails()->getAddress();
            $context['postal_code']=$current_account->getMedicalCenterDetails()->getPostalCode();
        }else if($current_account instanceof Doctor){
            $context['name']=$current_account->getDoctorDetails()->getDisplayName();
            $context['full_name']=$current_account->getDoctorDetails()->getFullName();
            $context['slmc_id']=$current_account->getDoctorDetails()->getSlmcId();
            $context['nic']=$current_account->getDoctorDetails()->getNic();
            $context['category']=$current_account->getDoctorDetails()->getCategory();
            $context['email']=$current_account->getDoctorDetails()->getEmail();
            $context['phone_number']=$current_account->getDoctorDetails()->getPhoneNumber();

        }
        //parent::loadOnlyIfUserIsOfType(Account::class, 'ProfilePage.html.twig',array("context"=>$context));
        $this->render("ProfilePage.html.twig",array("context"=>$context),$current_account);
    }
}