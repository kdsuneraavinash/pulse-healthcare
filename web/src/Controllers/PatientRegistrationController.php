<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Patient\PatientDetails;
use Pulse\Models\Exceptions;

class PatientRegistrationController extends BaseController
{
    /**
     * @param MedicalCenter $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function post(MedicalCenter $currentAccount)
    {
        $name = $this->httpHandler()->postParameter('name');
        $email = $this->httpHandler()->postParameter('email');
        $phoneNumber = $this->httpHandler()->postParameter('phone_number');
        $nic = $this->httpHandler()->postParameter('nic');
        $address = $this->httpHandler()->postParameter('address');
        $postalCode = $this->httpHandler()->postParameter('postal');

        if (!($name == null || $address == null || $postalCode == null ||
            $email == null ||
            $phoneNumber == null || $nic == null)) {

            $patientDetails = new PatientDetails($name, $nic, $email, $phoneNumber, $address, $postalCode);

            try {
                $password = $currentAccount->createPatientAccount($patientDetails);
                $this->render('iframe/MedicalCenterCreatePatient.twig', array(
                    'requested_account_id' => $nic,
                    'account_password' => $password
                ), $currentAccount);
                return;
            } catch (Exceptions\AccountAlreadyExistsException $e) {
                $error = "Account $nic is already registered.";
            } catch (Exceptions\InvalidDataException $e) {
                $error = "Server side validation failed.";
            } catch (Exceptions\AccountNotExistException $e) {
                $error = "Account $nic cannot be signed in!";
            }
        } else {
            $error = 'Some fields are empty.';
        }

        $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/med_center/register/patient?error=$error");
    }
}