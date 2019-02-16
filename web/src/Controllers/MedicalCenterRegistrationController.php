<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\AlreadyLoggedInException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\PHSRCAlreadyInUse;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\MedicalCenter\MedicalCenterDetails;

class MedicalCenterRegistrationController extends BaseController
{
    /**
     */
    public function post()
    {
        $accountId = $this->getRequest()->getBodyParameter('account');
        $passwordRetype = $this->getRequest()->getBodyParameter('password');
        $password = $this->getRequest()->getBodyParameter('password_retype');
        $name = $this->getRequest()->getBodyParameter('name');
        $phsrc = $this->getRequest()->getBodyParameter('phsrc');
        $email = $this->getRequest()->getBodyParameter('email');
        $fax = $this->getRequest()->getBodyParameter('fax');
        $phoneNumber = $this->getRequest()->getBodyParameter('phone_number');
        $address = $this->getRequest()->getBodyParameter('address');
        $postalCode = $this->getRequest()->getBodyParameter('postal_code');

        if ($password != $passwordRetype){
            $error = 'Password and retype password mismatch.';
            header("Location: http://$_SERVER[HTTP_HOST]/medi?error=$error");
            exit;
        }

        if ($accountId == null || $password == null || $name == null ||
            $phsrc == null || $email == null || $fax == null ||
            $phoneNumber == null || $address == null || $postalCode == null) {
            $error = 'Some fields are empty.';
            header("Location: http://$_SERVER[HTTP_HOST]/medi?error=$error");
            exit;
        }

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
        }

        if (isset($error)){
            header("Location: http://$_SERVER[HTTP_HOST]/medi?error=$error");
            exit;
        }

        header("Location: http://$_SERVER[HTTP_HOST]/profile");
        exit;
    }


    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        $accountId = $this->getCurrentAccountId();
        if ($accountId == null) {
            $this->render('MedicalCenterRegistration.html.twig', array(), $accountId);
        } else {
            $this->render('AlreadyLoggedIn.html.twig', array(), $accountId);
        }
    }
}