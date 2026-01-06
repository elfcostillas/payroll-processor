<?php

namespace App\Decorator\Payroll;

class OvertimeDecorator implements IHours
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
        return $this->hours->compute() * 1.25; 
    }
}
