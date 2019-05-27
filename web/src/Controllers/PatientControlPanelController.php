<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\Exceptions\NoPrescriptionsException;
use Pulse\Models\Patient\Patient;

class PatientControlPanelController extends BaseController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get()
    {
        parent::loadOnlyIfUserIsOfType(Patient::class, 'ControlPanelPatientPage.html.twig');
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getPatientTimelineIframe()
    {
        $currentAccount = $this->getCurrentAccount();
        try {
            if ($currentAccount instanceof Patient) {
                $parsedPrescriptions = $currentAccount->getParsedPrescriptions();
                $this->render('iframe/PatientTimelineIFrame.htm.twig', array('prescriptions' => $parsedPrescriptions), $currentAccount);
            } else {
                $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
            }
        } catch (InvalidDataException $e) {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/404");
        } catch (NoPrescriptionsException $e) {
            $this->render('iframe/NoPrescriptions.html.twig', array('prescriptions' => array()), $currentAccount);
            return;
        }
    }
}