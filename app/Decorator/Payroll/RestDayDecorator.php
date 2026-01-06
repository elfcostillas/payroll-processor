<?php

namespace App\Decorator\Payroll;

class RestDayDecorator implements IHours
{
    public function __construct(protected IHours $hours)
    {
    
    }

    public function getType()
    {
        return $this->hours->getType();
    }

    public function getPayType()
    {
        return $this->hours->getPayType();
    }

    public function compute()
    {
        return ($this->getPayType() == 1) ? $this->hours->compute() * 0.3 : $this->hours->compute() * 1.3; 
    }
}
