<?php

namespace Pulse\Models\Prescription;
use Pulse\Components\Database;

class MediCard
{
    private $name;
    private $dose;
    private $frequency;
    PRIVATE $time;
    private $comment;

    /**
     * MediCard constructor
     */
    public function __construct($name, $dose, $frequency, $time, $comment)
    {
        $this->name = $name;
        $this->dose = $dose;
        $this->frequency = $frequency;
        $this->time = $time;
        $this->comment = $comment;
    }

    public function saveInDatabase()
    {
        Database::insert('medi_cards', array(
            'name' => $this->getName(),
            'dose' => $this->getDose(),
            'frequency' => $this->getFrequency(),
            'time' => $this->getTime(),
            'comment' => $this->getComment(),
        ));
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDose()
    {
        return $this->dose;
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

}
