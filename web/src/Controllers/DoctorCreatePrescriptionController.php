<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components;
use Pulse\Models\Doctor\Doctor;

class DoctorCreatePrescriptionController extends BaseController
{
    public function post()
    {
        $currentAccount = $this->getCurrentAccount();
        $error=null;

        if ($currentAccount instanceof Doctor) {
            $type = $this->httpHandler()->postParameter('type');
            $days = $this->httpHandler()->postParameter('days');
            $comments = $this->httpHandler()->postParameter('comments');

            Components\Logger::log($type,$days,$comments);

            if (!($type == null)) {
                try {
                    $this->render('iframe/DoctorCreatePrescription.htm.twig', array(
                        'requested_type' => $type
                    ), $currentAccount);
                } catch (\Twig_Error_Loader $e) {
                } catch (\Twig_Error_Runtime $e) {
                } catch (\Twig_Error_Syntax $e) {
                }
            } else {
                $error = 'Enter type of illness.';
            }
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/doctor/create/prescription?error=$error");
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }
}