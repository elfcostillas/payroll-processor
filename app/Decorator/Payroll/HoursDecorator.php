<?php

namespace App\Decorator\Payroll;

class HoursDecorator implements IHours
{
    //
    public function __construct(protected IHours $hours)
    {
    
    }
    /*
    *   Day type : regular, restday  can be refactored 2 regular day and special day
    */
    public function getHoursType()
    {
        return $this->hours->getHoursType();
    }

    public function getDayType()
    {
        return $this->hours->getDayType();
    }

    public function getPayType()
    {
        return $this->hours->getPayType();
    }

    public function compute()
    {
        return $this->hours->compute();
    }
}
