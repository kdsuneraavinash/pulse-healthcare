<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 4/10/19
 * Time: 12:33 AM
 */

namespace Pulse\Models\Search;

use Pulse\Models\Interfaces\ISearchable;


class SearchContext{


    public static function search($iSearchable){
        if($iSearchable instanceof ISearchable){
            return $iSearchable->search();
        }
    }
}