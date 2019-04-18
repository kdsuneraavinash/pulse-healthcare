<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 4/10/19
 * Time: 12:33 AM
 */

namespace Pulse\Models\Search;

use Pulse\Models\Interfaces\DoctorCategoryContext;
use Pulse\Models\Interfaces\DoctorNoCategoryContext;
use Pulse\Models\Interfaces\PatientNICContext;
use Pulse\Models\Interfaces\PatientNoNICContext;

class SearchContext{


    public static function search($ISearchable){

        if($ISearchable instanceof DoctorCategoryContext || $ISearchable instanceof DoctorNoCategoryContext || $ISearchable instanceof PatientNICContext || $ISearchable instanceof PatientNoNICContext){
            $ISearchable->search();
        }

    }
}