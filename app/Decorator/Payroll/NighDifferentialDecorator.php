<?php

namespace App\Decorator\Payroll;

class NighDifferentialDecorator implements IHours
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
        return match($this->getType()){
            'overtime' => $this->hours->compute() * 1.1 ,
            'regular' => $this->hours->compute() * 0.1 ,
            'restday' => $this->hours->compute() * 0.1 ,
        };
    }
}
