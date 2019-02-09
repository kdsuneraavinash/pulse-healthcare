<?php declare(strict_types=1);

namespace Pulse\Models;

use DB;
use Pulse\BaseModel;

class Person extends BaseModel
{
    private $id;
    private $firstName;
    private $lastName;
    private $age;

    public function __construct(int $id, string $firstName, string $lastName, int $age)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
    }

    function saveInDatabase()
    {
//        $existQuery = DB::query("SELECT * FROM test WHERE ID = %i;", $this->id);
//        if (count($existQuery) == 0) {
//            DB::insert('test', array(
//                'ID' => $this->id,
//                'LastName' => $this->lastName,
//                'FirstName' => $this->firstName,
//                'Age' => $this->age,
//            ));
//        }

    }

    static function loadAllFromDatabase()
    {

    }
}