<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\AlreadyLoggedInException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\SLMCAlreadyInUse;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\StaticLogger;
use Pulse\Utils;

class DoctorRegistrationController extends BaseController
{

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
     *
     */
    public function post()
    {
        $currentAccountId = $this->getCurrentAccountId();

        //TODO: check whether the current logged user is other than medical center
        if ($currentAccountId != null) {
            $fullName = $this->getRequest()->getBodyParameter('full_name');
            $name = $this->getRequest()->getBodyParameter('name');
            $category = $this->getRequest()->getBodyParameter('category');
            $slmcId = $this->getRequest()->getBodyParameter('slmc_id');
            $email = $this->getRequest()->getBodyParameter('email');
            $phoneNumber = $this->getRequest()->getBodyParameter('phone_number');
            $nic = $this->getRequest()->getBodyParameter('nic');
            $password = $this->getRequest()->getBodyParameter('password');

            if (!($fullName == null || $name == null || $category == null ||
                $slmcId == null || $email == null ||
                $phoneNumber == null || $nic == null || $password == null)) {

                $doctorDetails = new DoctorDetails($fullName, $name, $category, $slmcId, $email, $phoneNumber);

                try {
                    Doctor::requestRegistration($nic, $doctorDetails, $password);
                } catch (AccountAlreadyExistsException $e) {
                    $error = "Account $nic is already registered.";
                } catch (InvalidDataException $e) {
                    $error = "Server side validation failed.";
                } catch (AccountNotExistException $e) {
                    $error = "Account name $currentAccountId cannot be signed in!";
                } catch (AlreadyLoggedInException $e) {
                    $error = "Account name $currentAccountId already logged in.";
                } catch (SLMCAlreadyInUse $e) {
                    $error = "A doctor is already registered using the given SLMC id.";
                }

                if (!isset($error)) {
                    header("Location: http://$_SERVER[HTTP_HOST]/profile");
                    exit;
                }
            } else {
                StaticLogger::loggerWarn("A field was null when registering a doctor by POST: " .
                    "for Account $nic and IP " . Utils::getClientIP());
                $error = 'Some fields are empty.';
                header("Location: http://$_SERVER[HTTP_HOST]/register/doctor?error$error");
            }
        } else {
            header("Location: http://$_SERVER[HTTP_HOST]");
            exit;
        }
    }
}