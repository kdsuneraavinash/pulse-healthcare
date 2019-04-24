<?php

namespace Pulse\Models\Prescription;

use Pulse\Components\Database;
use Pulse\Components\Logger;
use Pulse\Components\PureSqlStatement;
use Pulse\Models\Exceptions\AccountNotExistException;
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
     * @throws AccountNotExistException
     */
    public function saveInDatabase()
    {
        if ($this->getPrescriptionId() != null) {
            throw new InvalidDataException('Tried to re-save already saved prescription');
        }

        $this->validateFields();
        Database::insert('prescriptions', array(
                'patient_id' => $this->getPatientId(),
                'doctor_id' => $this->getDoctorId()
            )
        );

         $this->prescriptionId = Database::lastInsertedId();
         $this->saveMedicationsInDatabase();
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
     * @throws AccountNotExistException
     */
    public function validateFields()
    {
        // Validate all medications and throw an error if invalid
        foreach ($this->getMedications() as $medication) {
            $medicationsValid = $medication->validate();
            if (!$medicationsValid) {
                throw new InvalidDataException("Medication Details Validation Failed.");
            }
        };

        // Check whether doctor ID exists (if not throw an error)
        $query = Database::queryFirstRow(
            "SELECT account_id FROM doctors WHERE account_id=:account_id",
            array("account_id" => $this->getDoctorId())
        );
        if ($query == null) {
            throw new AccountNotExistException($this->getDoctorId());
        }

        // Check whether Patient ID exists (if not throw an error)
        $query = Database::queryFirstRow(
            "SELECT account_id FROM patients WHERE account_id=:account_id",
            array("account_id" => $this->getPatientId())
        );
        if ($query == null) {
            throw new AccountNotExistException($this->getPatientId());
        }
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