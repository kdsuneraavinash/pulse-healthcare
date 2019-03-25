<?php

namespace Pulse\Models\Prescription;

use Pulse\Components\Database;
use Pulse\Models\Prescription\MediCard;

class Prescription
{
    private $patientNIC;
    private $date;
    private $mediCards = array();

    public function __construct($patientNIC, $date, array $mediCards)
    {
        $this->patientNIC = $patientNIC;
        $this->date = $date;
        $this->mediCards = $mediCards;
    }


    public function saveInDatabase()
    {
        Database::insert('prescriptions', array(
            'patientNIC' => $this->getPatientNIC(),
            'date' => $this->getDate(),
        ));

        $this->saveMediCardsInDatabase();

    }

    public function saveMediCardsInDatabase(){
        $mediCardObjects=$this->getMediCards();

        foreach($mediCardObjects as $mediCard){
            $mediCard->saveInDatabase();

        };
    }

    public function getPatientNIC()
    {
        return $this->patientNIC;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getMediCards(): array
    {
        return $this->mediCards;
    }


}