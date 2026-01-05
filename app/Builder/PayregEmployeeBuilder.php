<?php

namespace App\Builder;
use App\Builder\IPayregEmployee;

class PayregEmployeeBuilder implements IPayregEmployee
{
    //
    protected $employeeObject;
    protected $schema;

    protected $fields;

    /*
    * Set employee
    * @param mixed $employeeObject
    */

    public function setEmployee($employeeObject){
        $this->employeeObject = $employeeObject;
        return $this;
    }

    /*
    * Set schema and fields
    * @param mixed $schema database schema
    */

    public  function setSchema($schema){
        $this->schema = $schema;

        foreach($this->schema as $dataBaseColumn){
            $this->fields[$dataBaseColumn] = null;
        }
        unset($this->fields['line_id']);

        return $this;
    }

    public function computeLate(){

    }

    public function computeUnderTime(){

    }

    public function computeAbsence(){

    }

    public function computeBasicPay(){

    }


}
