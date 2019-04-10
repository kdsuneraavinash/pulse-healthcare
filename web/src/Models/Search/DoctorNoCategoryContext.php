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

class DoctorNoCategoryContext implements ISearchable{

    private $slmcId;
    private $name;

    /**
     * DoctorNoCategoryContext constructor.
     * @param $slmcId
     * @param $name
     */
    public function __construct(?string $slmcId, ?string $name)
    {
        $this->slmcId = $slmcId;
        $this->name = $name;
    }


    public function search()
    {
        $sqlKeys = array();
        if ($this->name == null) {
            $nameArr = array();
        } else {
            // Split by space
            $nameArr = explode(" ", $this->name);
        }

        if ($this->slmcId != null) {
            $slmcSQL = "if(slmc_id LIKE :slmc_id, " . Definitions::SLMC_RELEVANCE_WEIGHT . ", 0)";
            $sqlKeys["slmc_id"] = "%$this->slmcId%";
        } else {
            $slmcSQL = "0";
        }

        if ($this->name != null) {
            $nameSQL = array();
            for ($i = 0; $i < sizeof($nameArr); $i++) {
                $key = $nameArr[$i];
                $nameKeyStr = "name_part_$i";
                $nameSQLi = "if(full_name LIKE :$nameKeyStr, " . Definitions::NAME_RELEVANCE_WEIGHT . ", 0)";
                $sqlKeys[$nameKeyStr] = "%$key%";
                array_push($nameSQL, $nameSQLi);
            }
            $nameSQL = implode(" + ", $nameSQL);
        } else {
            $nameSQL = "0";
        }


        if ($this->slmcId == null && $this->name == null) {
            // Nothing given
            return array();
        } else {
            /**
             * SELECT *, ( (0) + (if (full_name LIKE '%Saman%', 4, 0) ))  as relevance
             * FROM doctor_details HAVING relevance > 0 ORDER BY relevance DESC LIMIT 25
             */
            $query = "SELECT *, ( ($slmcSQL) + ($nameSQL) )  as relevance
                          FROM doctor_details
                          HAVING relevance > 0
                          ORDER BY relevance DESC";
        }

        $result = Database::query($query, $sqlKeys);
        return $result;

    }


}