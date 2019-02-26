<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\AccountRejectedException;
use Pulse\Exceptions\AlreadyLoggedInException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\PHSRCAlreadyInUse;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\MedicalCenter\MedicalCenterDetails;
use Pulse\StaticLogger;
use Pulse\Utils;

class MedicalCenterRegistrationController extends BaseController
{
    /**
     */
    public function post()
    {
        $accountId = $this->getRequest()->getBodyParameter('account');
        $password = $this->getRequest()->getBodyParameter('password');
        $passwordRetype = $this->getRequest()->getBodyParameter('password_retype');
        $name = $this->getRequest()->getBodyParameter('name');
        $phsrc = $this->getRequest()->getBodyParameter('phsrc');
        $email = $this->getRequest()->getBodyParameter('email');
        $fax = $this->getRequest()->getBodyParameter('fax');
        $phoneNumber = $this->getRequest()->getBodyParameter('phone_number');
        $address = $this->getRequest()->getBodyParameter('address');
        $postalCode = $this->getRequest()->getBodyParameter('postal');

        if ($password == $passwordRetype) {

            if (!($accountId == null || $password == null || $name == null ||
                $phsrc == null || $email == null ||
                $phoneNumber == null || $address == null || $postalCode == null)) {

                $medicalCenterDetails = new MedicalCenterDetails($name, $phsrc, $email, $fax, $phoneNumber,
                    $address, $postalCode);

                try {
                    MedicalCenter::requestRegistration($accountId, $medicalCenterDetails, $password);
                } catch (AccountAlreadyExistsException $e) {
                    $error = "Account name $accountId already taken.";
                } catch (AccountNotExistException $e) {
                    $error = "Account name $accountId cannot be signed in!";
                } catch (AlreadyLoggedInException $e) {
                    $error = "Account name $accountId already logged in.";
                } catch (InvalidDataException $e) {
                    $error = "Server side validation failed.";
                } catch (PHSRCAlreadyInUse $e) {
                    $error = "PHSRC already registered";
                } catch (AccountRejectedException $e) {
                    $error = "Server error. Please try again";
                }

                if (!isset($error)) {
                    header("Location: http://$_SERVER[HTTP_HOST]/profile");
                    exit;
                }
            } else {
                StaticLogger::loggerWarn("A field was null when registering a medical center by POST: " .
                    "for Account $accountId and IP " . Utils::getClientIP());

                $error = 'Some fields are empty.';
            }


        } else {
            $error = 'Password and retype password mismatch.';
        }
        header("Location: http://$_SERVER[HTTP_HOST]/register/medi?error=$error&name=$name" .
            "&phsrc=$phsrc&email=$email&fax=$fax&phone_number=$phoneNumber&address=$address&postal=$postalCode");
        exit;
    }


    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $account = $this->getCurrentAccount();
        if ($account == null) {
            $this->render('MedicalCenterRegistration.html.twig', array(
                'name' => $this->getRequest()->getQueryParameter('name'),
                'phsrc' => $this->getRequest()->getQueryParameter('phsrc'),
                'email' => $this->getRequest()->getQueryParameter('email'),
                'fax' => $this->getRequest()->getQueryParameter('fax'),
                'phone_number' => $this->getRequest()->getQueryParameter('phone_number'),
                'address' => $this->getRequest()->getQueryParameter('address'),
                'postal' => $this->getRequest()->getQueryParameter('postal')
            ), $account);
        } else {
            $this->render('AlreadyLoggedIn.html.twig', array(), $account);
        }
    }
}