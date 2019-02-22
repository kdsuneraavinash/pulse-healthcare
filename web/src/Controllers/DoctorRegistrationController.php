<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\AlreadyLoggedInException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\PHSRCAlreadyInUse;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\StaticLogger;
use Pulse\Utils;

class DoctorRegistrationController extends BaseController
{
    /**
     */
//    public function post()
//    {
//        $accountId = $this->getRequest()->getBodyParameter('account');
//        $password = $this->getRequest()->getBodyParameter('password');
//        $passwordRetype = $this->getRequest()->getBodyParameter('password_retype');
//        $name = $this->getRequest()->getBodyParameter('name');
//        $phsrc = $this->getRequest()->getBodyParameter('phsrc');
//        $email = $this->getRequest()->getBodyParameter('email');
//        $fax = $this->getRequest()->getBodyParameter('fax');
//        $phoneNumber = $this->getRequest()->getBodyParameter('phone_number');
//        $address = $this->getRequest()->getBodyParameter('address');
//        $postalCode = $this->getRequest()->getBodyParameter('postal');
//
//        if ($password == $passwordRetype) {
//
//            if (!($accountId == null || $password == null || $name == null ||
//                $phsrc == null || $email == null ||
//                $phoneNumber == null || $address == null || $postalCode == null)) {
//
//                $medicalCenterDetails = new MedicalCenterDetails($name, $phsrc, $email, $fax, $phoneNumber,
//                    $address, $postalCode);
//
//                try {
//                    MedicalCenter::requestRegistration($accountId, $medicalCenterDetails, $password);
//                } catch (AccountAlreadyExistsException $e) {
//                    $error = "Account name $accountId already taken.";
//                } catch (AccountNotExistException $e) {
//                    $error = "Account name $accountId cannot be signed in!";
//                } catch (AlreadyLoggedInException $e) {
//                    $error = "Account name $accountId already logged in.";
//                } catch (InvalidDataException $e) {
//                    $error = "Server side validation failed.";
//                } catch (PHSRCAlreadyInUse $e) {
//                    $error = "PHSRC already registered";
//                }
//
//                if (!isset($error)) {
//                    header("Location: http://$_SERVER[HTTP_HOST]/profile");
//                    exit;
//                }
//            } else {
//                StaticLogger::loggerWarn("A field was null when registering a medical center by POST: " .
//                    "for Account $accountId and IP " . Utils::getClientIP());
//
//                $error = 'Some fields are empty.';
//            }
//
//
//        } else {
//            $error = 'Password and retype password mismatch.';
//        }
//        header("Location: http://$_SERVER[HTTP_HOST]/register/medi?error=$error&name=$name" .
//            "&phsrc=$phsrc&email=$email&fax=$fax&phone_number=$phoneNumber&address=$address&postal=$postalCode");
//        exit;
//    }


    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $accountId = $this->getCurrentAccountId();
        if ($accountId != null) {
            $this->render('DoctorRegistration.html.twig', array(), $accountId);
        } else {
            header("Location: http://$_SERVER[HTTP_HOST]");
            exit;
        }
    }


    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * TODO: check whether the current logged user is other than medical center
     */
    public function post()
    {
        $currentAccountId = $this->getCurrentAccountId();
        if ($currentAccountId != null) {
            $fullName=$this->getRequest()->getBodyParameter('fullName');
            $name=$this->getRequest()->getBodyParameter('name');
            $category=$this->getRequest()->getBodyParameter('category');
            $slmcID=$this->getRequest()->getBodyParameter('slmcID');
            $email=$this->getRequest()->getBodyParameter('email');
            $phoneNumber=$this->getRequest()->getBodyParameter('phoneNumber');

            $NIC = $this->getRequest()->getBodyParameter('NIC');
            $password = $this->getRequest()->getBodyParameter('password');

            if (!($fullName == null || $name == null || $category == null ||
                $slmcID == null || $email == null ||
                $phoneNumber == null || $NIC == null || $password == null)) {

                $doctorDetails = new doctorDetails($fullName,$name, $category, $slmcID,$email, $phoneNumber);

                try {
                    Doctor::requestRegistration($NIC, $doctorDetails, $password);
                } catch (AccountAlreadyExistsException $e) {
                    $error = "Account $NIC is already registered.";
                }catch (InvalidDataException $e) {
                    $error = "Server side validation failed.";
                }

                if (!isset($error)) {
                    header("Location: http://$_SERVER[HTTP_HOST]/profile");
                    exit;
                }
            } else {
                StaticLogger::loggerWarn("A field was null when registering a doctor by POST: " .
                    "for Account $NIC and IP " . Utils::getClientIP());

                $error = 'Some fields are empty.';
            }
        } else {
            header("Location: http://$_SERVER[HTTP_HOST]");
            exit;
        }
    }
}