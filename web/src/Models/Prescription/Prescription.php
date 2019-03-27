<?php

namespace Pulse\Models\Prescription;

use Pulse\Components\Database;
use Pulse\Models\Doctor\Doctor;
use Pulse\Models\Exceptions\InvalidDataException;

class Prescription
{
    private $prescriptionId;
    private $patientId;
    private $doctorId;
    private $date;
    private $medications;

    /**
     * Prescription constructor.
     * If prescription Id in null then it means it is a new one. Else has to load.
     * @param string|null $prescriptionId
     * @param string $patientId
     * @param string $doctorId
     * @param array $medications
     */
    public function __construct(?string $prescriptionId, string $patientId, string $doctorId, array $medications)
    {
        $this->patientId = $patientId;
        $this->doctorId = $doctorId;
        $this->medications = $medications;
        $this->prescriptionId = $prescriptionId;

        // Date have to be today
        $this->date = date('m/d/Y', time());
    }

    /**
     * @throws InvalidDataException
     */
    public function saveInDatabase()
    {
        if ($this->getPrescriptionId() != null) {
            throw new InvalidDataException('Tried to resave already saved prescription');
        }

        $this->validateFields();

        /*
         * TODO: Insert Prescription Details (DATE, PATIENT_ID) to Database
         * TODO: Retrieve Prescription ID of the inserted record
         *
         * Uncomment following lines
         */

        // $this->prescriptionId = Database::lastInsertedId();
        // $this->saveMedicationsInDatabase();
    }

    /**
     * @throws InvalidDataException
     */
    public function saveMedicationsInDatabase()
    {
        if ($this->getPrescriptionId() == null) {
            throw new InvalidDataException('Prescription ID was empty when trying to save medications');
        }

        $medications = $this->getMedications();

        foreach ($medications as $medication) {
            $medication->saveInDatabase($this->getPrescriptionId());
        };
    }

    /**
     * @throws InvalidDataException
     */
    public function validateFields()
    {
        // Validate all medications and throw an error if invalid
        foreach ($this->getMedications() as $medication) {
            $medicationsValid = $medication->validate();
            if (!$medicationsValid){
                throw new InvalidDataException("Medication Details Validation Failed.");
            }
        };

        // TODO: Check whether doctor ID exists (if not throw an error)
        // TODO: Check whether Patient ID exists (if not throw an error)
    }

    public function getPrescriptionId(): ?string
    {
        return $this->prescriptionId;
    }

    public function getPatientId(): string
    {
        return $this->patientId;
    }

    public function getDoctorId(): string
    {
        return $this->doctorId;
    }

    public function getMedications(): array
    {
        return $this->medications;
    }
}