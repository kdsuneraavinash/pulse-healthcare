<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 4/10/19
 * Time: 12:20 AM
 */

namespace Pulse\Models\Interfaces;
use Pulse\Components\Database;
use Pulse\Definitions;


class PatientNICContext implements ISearchable {

    private $nic;
    private $name;
    private $address;

    /**
     * PatientNICContext constructor.
     * @param $nic
     * @param $name
     * @param $address
     */
    public function __construct(?string $nic,?string $name,?string $address)
    {
        $this->nic = $nic;
        $this->name = $name;
        $this->address = $address;
    }


    public function search()
    {
        $sqlKeys = array();

        if ($this->name == null){
            $nameArr = array();
        }else{
            // Split by space
            $nameArr = explode(" ", $this->name);
        }

        if ($this->address == null){
            $addressArr = array();
        }else{
            // Split by space
            $addressArr = explode(" ", $this->address);
        }

        if ($this->name != null) {
            $nameSQL = array();
            for($i = 0; $i < sizeof($nameArr); $i++){
                $key = $nameArr[$i];
                $nameKeyStr = "name_part_$i";
                $nameSQLi = "if (name LIKE :$nameKeyStr, ". Definitions::NAME_RELEVANCE_WEIGHT .", 0)";
                $sqlKeys[$nameKeyStr] = "%$key%";
                array_push($nameSQL, $nameSQLi);
            }
            $nameSQL =implode(" + ", $nameSQL);
        }else{
            $nameSQL = "0";
        }

        if ($this->address != null) {
            $addressSQL = array();
            for($i = 0; $i < sizeof($addressArr); $i++){
                $key = $addressArr[$i];
                $addressKeyStr = "address_part_$i";
                $addressSQLi = "if(address LIKE :$addressKeyStr, ". Definitions::ADDRESS_RELEVANCE_WEIGHT .", 0)";
                $sqlKeys[$addressKeyStr] = "%$key%";
                array_push($addressSQL, $addressSQLi);
            }
            $addressSQL =implode(" + ", $addressSQL);
        }else{
            $addressSQL = "0";
        }

        if ($this->nic != null) {
            $query = "SELECT * FROM patient_details WHERE nic = :nic";
            $result = Database::queryFirstRow($query, array("nic" => $this->nic));

            if ($result == null) {
                // Not matching NIC - SearchContext by other factors
                if ($this->address == null && $this->name == null) {
                    // No other given - no results
                    return array();
                }
                // If other factors given - go out of the if statements
            } else {
                // If matched - SearchContext no more
                $result['relevance'] = 100;
                return [$result];
            }
        }

        $query = "SELECT *, ( ($addressSQL) + ($nameSQL) )  as relevance
                          FROM patient_details
                          HAVING relevance > 0
                          ORDER BY relevance DESC";
        return Database::query($query, $sqlKeys);

    }

}