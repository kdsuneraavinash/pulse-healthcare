<?php declare(strict_types=1);

namespace Pulse\Controllers\API;

use Pulse\Models\AccountSession\Account;
use Pulse\Models\Exceptions\InvalidDataException;
use Pulse\Models\Exceptions\NoPrescriptionsException;
use Pulse\Models\Patient\Patient;

class TimelineController extends APIController
{
    /**
     * @param Account|null $currentAccount
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function timeline(?Account $currentAccount)
    {
        $jsonTemplate = 'api/Timeline.json.twig';
        try {
            if ($currentAccount instanceof Patient) {
                $parsedPrescriptions = $currentAccount->getParsedPrescriptions();
                $this->render($jsonTemplate, array('prescriptions' => $parsedPrescriptions,
                    'message' => 'Timeline Loaded', 'ok' => 'true'), $currentAccount);
            } else {
                $this->echoError($jsonTemplate, 'Current account is not a patient');
                return;
            }
        } catch (InvalidDataException $e) {
            $this->echoError($jsonTemplate, "Invalid Data Entries: {$e->getMessage()}");
        } catch (NoPrescriptionsException $e) {
            $this->render($jsonTemplate, array('prescriptions' => array(),
                'message' => 'Timeline Loaded', 'ok' => 'true'), $currentAccount);
        }
    }

}