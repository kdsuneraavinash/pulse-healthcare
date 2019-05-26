<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 4/10/19
 * Time: 12:33 AM
 */

namespace Pulse\Models\Search;

use Pulse\Models\Search\DoctorCategoryContext;
use Pulse\Models\Search\DoctorNoCategoryContext;
use Pulse\Models\Search\PatientNICContext;
use Pulse\Models\Search\PatientNoNICContext;


class SearchContext{


    public static function search($ISearchable){

        if($ISearchable instanceof DoctorCategoryContext || $ISearchable instanceof DoctorNoCategoryContext || $ISearchable instanceof PatientNICContext || $ISearchable instanceof PatientNoNICContext){
            return $ISearchable->search();
        }

    }
}