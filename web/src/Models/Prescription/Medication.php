<?php

namespace Pulse\Models\Prescription;

use Pulse\Components\Database;
use Pulse\Models\Exceptions\InvalidDataException;

class Medication
{
    private $medicationId;
    private $name;
    private $dose;
    private $frequency;
    PRIVATE $time;
    private $comment;
    private $prescriptionId;

    /**
     * Medication constructor
     * @param string|null $medicationId
     * @param string $prescriptionId
     * @param string $name
     * @param string $dose
     * @param string $frequency
     * @param string $time
     * @param string $comment
     */
    public function __construct(?string $medicationId, ?string $prescriptionId, string $name, string $dose, string $frequency, string $time, string $comment)
    {
        $this->medicationId = $medicationId;
        $this->prescriptionId = $prescriptionId;
        $this->name = $name;
        $this->dose = $dose;
        $this->frequency = $frequency;
        $this->time = $time;
        $this->comment = $comment;
    }

    /**
     * @return bool
     * @throws InvalidDataException
     */
    public function validate(): bool
    {
        if ($this->getMedicationId() !== null) {
            throw new InvalidDataException('Medication ID is already populated. (It has to be null to save in DB)');
        }

        if ($this->getPrescriptionId() !== null) {
            throw new InvalidDataException('Prescription ID is already populated. (It has to be null to save in DB)');
        }

        if ($this->getName() == null) {
            return false;
        }

        return true;
    }

    public function saveInDatabase(string $prescriptionId)
    {
        Database::insert('medications',
            array(
                'prescription_id' => $prescriptionId,
                'name' => $this->getName(),
                'dose' => $this->getDose(),
                'frequency' => $this->getFrequency(),
                'time' => $this->getTime(),
                'comment' => $this->getComment(),
            ));
    }

    public static function fromPrescription(string $prescriptionId)
    {
        $queries = Database::query("SELECT * FROM medications where prescription_id=:id",
            array('id' => $prescriptionId));
        if ($queries == null) {
            return array();
        }
        $medications = array();
        foreach ($queries as $query) {
            $medication = new Medication($query['id'],
                $query['prescription_id'], $query['name'], $query['dose'],
                $query['frequency'], $query['time'], $query['comment']);
            array_push($medications, $medication);
        }
        return $medications;
    }

    public function getMedicationId(): ?string
    {
        return $this->medicationId;
    }

    public function getPrescriptionId(): ?string
    {
        return $this->prescriptionId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDose(): string
    {
        return $this->dose;
    }

    public function getFrequency(): string
    {
        return $this->frequency;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

}
