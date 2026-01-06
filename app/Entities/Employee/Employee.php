<?php

namespace App\Entities\Employee;

class Employee
{
    //
    protected $stdObject;

    public function __construct($stdObject) {
        $this->stdObject = $stdObject;

    }

    /**
     * 2 = Daily
     * 1 = Semi Monthly
     **/
    
    public function getPayType(){
        return $this->stdObject->pay_type;
    }

    public function getBasicSalary(){
        return $this->stdObject->basic_salary;
    }
}
