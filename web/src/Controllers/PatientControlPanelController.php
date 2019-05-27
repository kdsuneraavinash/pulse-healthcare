<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\Exceptions\NoPrescriptionsException;
use Pulse\Models\Patient\Patient;

class PatientControlPanelController extends BaseController
{
    /**
     * @param Patient $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function get(Patient $currentAccount)
    {
        $this->renderWithNoContext('ControlPanelPatientPage.html.twig', $currentAccount);
    }

    /**
     * @param Patient $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getPatientTimelineIframe(Patient $currentAccount)
    {
        try {
            $parsedPrescriptions = $currentAccount->getParsedPrescriptions();
            $this->render('iframe/PatientTimelineIFrame.htm.twig', array('prescriptions' => $parsedPrescriptions), $currentAccount);
        } catch (InvalidDataException $e) {
            $this->redirectToErrorPage(404);
        } catch (NoPrescriptionsException $e) {
            $this->render('iframe/NoPrescriptions.html.twig', array('prescriptions' => array()), $currentAccount);
        }
    }
}