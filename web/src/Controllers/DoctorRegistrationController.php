<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Exceptions\SLMCAlreadyInUse;
use Pulse\Models\Doctor\DoctorDetails;
use Pulse\Models\MedicalCenter\MedicalCenter;
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
        $currentAccount = $this->getCurrentAccount();
        if ($currentAccount instanceof MedicalCenter) {
            $this->render('DoctorRegistration.html.twig', array(), $currentAccount->getAccountId());
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
        $currentAccount = $this->getCurrentAccount();

        if ($currentAccount instanceof MedicalCenter) {
            $fullName = $this->getRequest()->getBodyParameter('full_name');
            $displayName = $this->getRequest()->getBodyParameter('display_name');
            $category = $this->getRequest()->getBodyParameter('category');
            $slmcId = $this->getRequest()->getBodyParameter('slmc_id');
            $email = $this->getRequest()->getBodyParameter('email');
            $phoneNumber = $this->getRequest()->getBodyParameter('phone_number');
            $nic = $this->getRequest()->getBodyParameter('nic');

            if (!($fullName == null || $displayName == null || $category == null ||
                $slmcId == null || $email == null ||
                $phoneNumber == null || $nic == null)) {

                $doctorDetails = new DoctorDetails($nic, $fullName, $displayName, $category, $slmcId, $email, $phoneNumber);

                try {
                    $password = $currentAccount->createDoctorAccount($doctorDetails);
                    echo "Account ID: $nic <p>Password: <pre>$password</pre>";
                    exit;
                } catch (AccountAlreadyExistsException $e) {
                    $error = "Account $nic is already registered.";
                } catch (InvalidDataException $e) {
                    $error = "Server side validation failed.";
                } catch (AccountNotExistException $e) {
                    $error = "Account $nic cannot be signed in!";
                } catch (SLMCAlreadyInUse $e) {
                    $error = "A doctor is already registered using the given SLMC id.";
                }
            } else {
                StaticLogger::loggerWarn("A field was null when registering a doctor by POST: " .
                    "for Account $nic and IP " . Utils::getClientIP());
                $error = 'Some fields are empty.';
            }
            header("Location: http://$_SERVER[HTTP_HOST]/register/doctor?error=$error");
            exit;
        } else {
            header("Location: http://$_SERVER[HTTP_HOST]");
            exit;
        }
    }
}