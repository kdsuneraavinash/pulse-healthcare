<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\Doctor\DoctorDetails;
use Pulse\Models\MedicalCenter\MedicalCenter;
use Pulse\Models\Exceptions;

class DoctorRegistrationController extends BaseController
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
            $fullName = $this->httpHandler()->postParameter('full_name');
            $displayName = $this->httpHandler()->postParameter('display_name');
            $category = $this->httpHandler()->postParameter('category');
            $slmcId = $this->httpHandler()->postParameter('slmc_id');
            $email = $this->httpHandler()->postParameter('email');
            $phoneNumber = $this->httpHandler()->postParameter('phone_number');
            $nic = $this->httpHandler()->postParameter('nic');

            echo $fullName;

            if (!($fullName == null || $displayName == null || $category == null ||
                $slmcId == null || $email == null ||
                $phoneNumber == null || $nic == null)) {

                $doctorDetails = new DoctorDetails($nic, $fullName, $displayName, $category, $slmcId, $email, $phoneNumber);

                try {
                    $password = $currentAccount->createDoctorAccount($doctorDetails);

                    $this->render('iframe/MedicalCenterCreateDoctor.htm.twig', array(
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
                } catch (Exceptions\SLMCAlreadyInUse $e) {
                    $error = "A doctor is already registered using the given SLMC id.";
                }
            } else {
                $error = 'Some fields are empty.';
            }
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/med_center/register/doctor?error=$error");
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }
}