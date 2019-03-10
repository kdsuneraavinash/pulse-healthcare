<?php
/**
 * Created by IntelliJ IDEA.
 * User: Anju Chamantha
 * Date: 3/8/2019
 * Time: 10:07 PM
 */

class MediCard{
    private $mediCardID;
    private $medicine;
    private $dose;
    private $quantity;
    private $frequency;
    PRIVATE $time;
    private $comment;

    /**
 * MediCard constructor.
 * @param $mediCardID
 */public function __construct($mediCardID)
{
    $this->mediCardID = $mediCardID;
}

    /**
     * @return mixed
     */
    public function getMediCardID()
    {
        return $this->mediCardID;
    }


    /**
     * @return mixed
     */
    public function getMedicine()
    {
        return $this->medicine;
    }

    /**
     * @param mixed $medicine
     */
    public function setMedicine($medicine): void
    {
        $this->medicine = $medicine;
    }

    /**
     * @return mixed
     */
    public function getDose()
    {
        return $this->dose;
    }

    /**
     * @param mixed $dose
     */
    public function setDose($dose): void
    {
        $this->dose = $dose;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency($frequency): void
    {
        $this->frequency = $frequency;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }




}
