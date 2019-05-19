<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components\Logger;
use Pulse\Models\AccountSession\Account;
use Pulse\Models\AccountSession\Credentials;
use Pulse\Models\Admin\Admin;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Exceptions\AccountAlreadyExistsException;
use Pulse\Models\Exceptions\AccountNotExistException;
use Pulse\Models\Exceptions\AccountRejectedException;
use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Patient\Patient;
use Pulse\Models\Prescription\Medication;
use Pulse\Models\Prescription\Prescription;

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
        if ($current_account instanceof  Account){
            $context = $this->populate_with_account($current_account);
            $this->render("ProfilePage.html.twig", $context, $current_account);
        }else{
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]");
        }
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getChangePassword()
    {
        $current_account = $this->getCurrentAccount();
        if ($current_account instanceof  Account){
            $this->render("ChangePassword.html.twig", array(), $current_account);
        }else{
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]");
        }
    }

    public function postChangePassword()
    {
        $current_account = $this->getCurrentAccount();
        if ($current_account instanceof  Account){
            $prevPassword = $this->httpHandler()->postParameter("prev");
            $newPassword = $this->httpHandler()->postParameter("new");
            $retypePassword = $this->httpHandler()->postParameter("retype");
            Logger::log($retypePassword);
            if ($newPassword != null and $newPassword != "" and $newPassword == $retypePassword){
                try {
                    $isCorrect = Credentials::isPasswordCorrectOfUser($current_account->getAccountId(), $prevPassword);
                    if (!$isCorrect){
                        $msg = "Current Password Incorrect";
                        $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/changepsw?error=$msg");
                        return;
                    }
                    Credentials::setNewPasswordOfUser($current_account->getAccountId(), $newPassword);
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/profile");
                } catch (AccountNotExistException $e) {
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]");
                } catch (AccountAlreadyExistsException $e) {
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
                }
            }else{
                $msg = "Retype Password Mismatch";
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/changepsw?error=$msg");
            }
        }else{
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]");
        }
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getTimeline(){
        $currentAccount = $this->getCurrentAccount();
        try {
            if ($currentAccount instanceof Doctor) {
                $accountId = $this->httpHandler()->getParameter("user");
                $patient = Account::retrieveAccount($accountId, true);
                if ($patient instanceof Patient){
                    $prescriptions = $patient->getPrescriptions();
                    if (sizeof($prescriptions) == 0) {
                        throw new InvalidDataException("No Prescriptions");
                    }
                    $parsedPrescriptions = array();
                    foreach ($prescriptions as $prescription) {
                        $parsedMedications = array();
                        if ($prescription instanceof Prescription) {
                            foreach ($prescription->getMedications() as $medication) {
                                if ($medication instanceof Medication) {
                                    $parsedMedication = array(
                                        'id' => $medication->getMedicationId(),
                                        'name' => $medication->getName(),
                                        'dose' => $medication->getDose(),
                                        'frequency' => $medication->getFrequency(),
                                        'time' => $medication->getTime(),
                                        'comment' => $medication->getComment(),
                                    );
                                    array_push($parsedMedications, $parsedMedication);
                                }
                            }
                            $parsedPrescription = array(
                                'doctor' => $prescription->getDoctorId(),
                                'id' => $prescription->getPrescriptionId(),
                                'date' => $prescription->getDate(),
                                'patient' => $prescription->getPatientId(),
                                'medications' => $parsedMedications
                            );
                            array_push($parsedPrescriptions, $parsedPrescription);
                        }
                    }

                    $this->render('PatientTimeline.htm.twig', array('prescriptions' => $parsedPrescriptions), $currentAccount);
                    return;
                }
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
            } else {
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
            }
        } catch (InvalidDataException $e) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/404");
        } catch (AccountNotExistException $e) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/404");
        } catch (AccountRejectedException $e) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/404");
        }
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getShowProfile()
    {
        $accountId = $this->httpHandler()->getParameter("user");
        $current_account = $this->getCurrentAccount();
        if ($accountId == null){
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/404?a=$accountId");
            exit();
        }
        try {
            $account = Account::retrieveAccount($accountId, true);
        } catch (AccountNotExistException|AccountRejectedException|InvalidDataException $e) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/404?a=$accountId");
            exit();
        }
        $context = $this->populate_with_account($account);
        $this->render("ProfilePage.html.twig", $context, $current_account);
    }

    function populate_with_account(Account $account): array
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