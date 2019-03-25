<?php declare(strict_types=1);

namespace Pulse\Controllers;

use Pulse\Components;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Prescription\MediCard;
use Pulse\Models\Prescription\Prescription;
//use Pulse\Models\Exceptions;

class DoctorCreatePrescriptionController extends BaseController
{
    function array2string($data){
        $log_a = "";
        foreach ($data as $key => $value) {
            if(is_array($value))    $log_a .= "[".$key."] => (". $this->array2string($value). ") \n";
            else                    $log_a .= "[".$key."] => ".$value."\n";
        }
        return $log_a;
    }

    public function post()
    {
        $currentAccount = $this->getCurrentAccount();
        $error=null;

        if ($currentAccount instanceof Doctor) {
            $data = $this->httpHandler()->postParameter('data');
            var_dump($data);

            Components\Logger::log($data['patientNIC']);
            Components\Logger::log($data['date']);
            Components\Logger::log($this->array2string($data['medCards']));

            $mediCardObjects = array();

            foreach($data as $item){

                $name= $item['name'];
                $dose= $item['dose'];
                $frequency= $item['frequency'];
                $time= $item['time'];
                $comment= $item['comment'];

                $mediCardObj=new MediCard($name,$dose,$frequency,$time,$comment);
                array_push($mediCardObjects,$mediCardObj);
            };

            $prescriptionObj = new Prescription(1,$mediCardObjects);

            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/control/doctor/create/prescription?error=$error");
        } else {
            $this->httpHandler()->redirect("http://$_SERVER[HTTP_HOST]/405");
        }
    }
}