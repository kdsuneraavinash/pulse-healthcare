<?php

namespace Pulse\Models\Prescription;
use Pulse\Components\Database;

class Prescription{
    private $prescriptionID;
    private $mediCards=array();


    public function __construct($prescriptionID, array $mediCards)
    {
        $this->prescriptionID = $prescriptionID;
        $this->mediCards = $mediCards;
    }

    public function saveInDatabase()
    {
        Database::insert('prescriptions', array(
            'name' => $this->getName(),
            'dose' => $this->getDose(),
            'frequency' => $this->getFrequency(),
            'time' => $this->getTime(),
            'comment' => $this->getComment(),
        ));
    }


}