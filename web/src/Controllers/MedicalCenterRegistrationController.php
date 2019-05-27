<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\MedicalCenter\MedicalCenterDetails;
use Pulse\Models\Exceptions;

class MedicalCenterRegistrationController extends BaseController
{
    /**
     */
    public function post()
    {
        $accountId = $this->httpHandler()->postParameter('account');
        $password = $this->httpHandler()->postParameter('password');
        $passwordRetype = $this->httpHandler()->postParameter('password_retype');
        $name = $this->httpHandler()->postParameter('name');
        $phsrc = $this->httpHandler()->postParameter('phsrc');
        $email = $this->httpHandler()->postParameter('email');
        $fax = $this->httpHandler()->postParameter('fax');
        $phoneNumber = $this->httpHandler()->postParameter('phone_number');
        $address = $this->httpHandler()->postParameter('address');
        $postalCode = (int) $this->httpHandler()->postParameter('postal');

        if ($password == $passwordRetype) {

            $error = null;
            if (!($accountId == null || $password == null || $name == null ||
                $phsrc == null || $email == null ||
                $phoneNumber == null || $address == null || $postalCode == null)) {

                $medicalCenterDetails = new MedicalCenterDetails($name, $phsrc, $email, $fax, $phoneNumber,
                    $address, (int) $postalCode);

                try {
                    MedicalCenter::requestRegistration($accountId, $medicalCenterDetails, $password);
                    $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/profile");
                } catch (Exceptions\AccountAlreadyExistsException $e) {
                    $error = "Account name $accountId already taken.";
                } catch (Exceptions\AccountNotExistException $e) {
                    $error = "Account name $accountId cannot be signed in!";
                } catch (Exceptions\AlreadyLoggedInException $e) {
                    $error = "Account name $accountId already logged in.";
                } catch (Exceptions\InvalidDataException $e) {
                    $error = "Server side validation failed.";
                } catch (Exceptions\PHSRCAlreadyInUse $e) {
                    $error = "PHSRC already registered";
                } catch (Exceptions\AccountRejectedException $e) {
                    $error = "Server error. Please try again";
                }
            } else {
                $error = 'Some fields are empty.';
            }

        } else {
            $error = 'Password and retype password mismatch.';
        }
        $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/register/medi?error=$error&name=$name" .
            "&phsrc=$phsrc&email=$email&fax=$fax&phone_number=$phoneNumber&address=$address&postal=$postalCode");
    }


    /**
     * @param Account|null $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get(?Account $currentAccount)
    {
        if ($currentAccount == null) {
            $this->render('MedicalCenterRegistration.html.twig', array(
                'name' => $this->httpHandler()->getParameter('name'),
                'phsrc' => $this->httpHandler()->getParameter('phsrc'),
                'email' => $this->httpHandler()->getParameter('email'),
                'fax' => $this->httpHandler()->getParameter('fax'),
                'phone_number' => $this->httpHandler()->getParameter('phone_number'),
                'address' => $this->httpHandler()->getParameter('address'),
                'postal' => $this->httpHandler()->getParameter('postal')
            ), $currentAccount);
        } else {
            $this->renderWithNoContext('AlreadyLoggedIn.html.twig', $currentAccount);
        }
    }
}