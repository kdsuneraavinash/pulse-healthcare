<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components;
use Pulse\Components\Utils;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Prescription\MediCard;
use Pulse\Models\Prescription\Prescription;

class DoctorCreatePrescriptionController extends BaseController
{
    public function post()
    {
        $currentAccount = $this->getCurrentAccount();
        $error = null;

        if ($currentAccount instanceof Doctor) {
            $data = $this->httpHandler()->postParameter('data');

            $patientNIC = $data['patientNIC'];
            $date = $data['date'];
            $medCards = $data['medCards'];


            Components\Logger::log(Utils::array2string($data));

            $mediCardObjects = array();

            foreach ($medCards as $item) {

                $name = $item['name'];
                $dose = $item['dose'];
                $frequency = $item['frequency'];
                $time = $item['time'];
                $comment = $item['comment'];

                $mediCardObj = new MediCard($name, $dose, $frequency, $time, $comment);
                array_push($mediCardObjects, $mediCardObj);
            };

            $prescriptionObj = new Prescription($patientNIC, $date, $mediCardObjects);
            $prescriptionObj->saveInDatabase();

            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/doctor/create/prescription?error=$error");
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }
}