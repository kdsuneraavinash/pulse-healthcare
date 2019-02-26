<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Exceptions\AccountAlreadyExistsException;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\InvalidDataException;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Patient\PatientDetails;
use Pulse\StaticLogger;
use Pulse\Utils;

class PatientRegistrationController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function post()
    {
        $currentAccount = $this->getCurrentAccount();

        if ($currentAccount instanceof MedicalCenter) {
            $name = $this->getRequest()->getBodyParameter('name');
            $email = $this->getRequest()->getBodyParameter('email');
            $phoneNumber = $this->getRequest()->getBodyParameter('phone_number');
            $nic = $this->getRequest()->getBodyParameter('nic');
            $address = $this->getRequest()->getBodyParameter('address');
            $postalCode = $this->getRequest()->getBodyParameter('postal');

            if (!($name == null || $address == null || $postalCode == null ||
                $email == null ||
                $phoneNumber == null || $nic == null)) {

                $patientDetails = new PatientDetails($name, $nic, $email, $phoneNumber, $address, $postalCode);

                try {
                    $password = $currentAccount->createPatientAccount($patientDetails);

                    $this->render('iframe/MedicalCenterCreatePatient.htm.twig', array(
                        'requested_account_id' => $nic,
                        'account_password' => $password
                    ), $currentAccount);
                    return;
                } catch (AccountAlreadyExistsException $e) {
                    $error = "Account $nic is already registered.";
                } catch (InvalidDataException $e) {
                    $error = "Server side validation failed.";
                } catch (AccountNotExistException $e) {
                    $error = "Account $nic cannot be signed in!";
                }
            } else {
                StaticLogger::loggerWarn("A field was null when registering a patient by POST: " .
                    "for Account $nic and IP " . Utils::getClientIP());
                $error = 'Some fields are empty.';
            }
            header("Location: http://$_SERVER[HTTP_HOST]/control/med_center/register/patient?error=$error");
            exit;
        } else {
            header("Location: http://$_SERVER[HTTP_HOST]/405");
            exit;
        }
    }
}