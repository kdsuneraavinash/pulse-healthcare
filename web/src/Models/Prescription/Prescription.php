<?php
/**
 * Created by IntelliJ IDEA.
 * User: Anju Chamantha
 * Date: 3/7/2019
 * Time: 10:53 AM
 */

class Prescription{
    private $prescriptionID;
    private $typeOfIllness;
    private $overallComments =null;
    private $mediCards=array();

    /**
     * Prescription constructor.
     * @param $prescriptionID
     * @param $typeOfIllness
     */
    public function __construct($prescriptionID, $typeOfIllness)
    {
        $this->prescriptionID = $prescriptionID;
        $this->typeOfIllness = $typeOfIllness;
    }

    /**
     * @param null $overallComments
     */
    public function setOverallComments($overallComments): void
    {
        $this->overallComments = $overallComments;
    }

    /**
     * @param array $mediCards
     */
    public function setMediCards(array $mediCards): void
    {
        $this->mediCards = $mediCards;
    }

    /**
     * @return mixed
     */
    public function getPrescriptionID()
    {
        return $this->prescriptionID;
    }

    /**
     * @return mixed
     */
    public function getTypeOfIllness()
    {
        return $this->typeOfIllness;
    }

    /**
     * @return null
     */
    public function getOverallComments()
    {
        return $this->overallComments;
    }

    /**
     * @return array
     */
    public function getMediCards(): array
    {
        return $this->mediCards;
    }


}