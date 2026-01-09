<?php

namespace App\Decorator\Payroll;

class NighDifferentialDecorator extends HoursDecorator implements IHours
{

    public function compute()
    {
        
        return ($this->getHoursType() =='NIGHTSHIFT') ?  $this->hours->compute() * 0.1 :  $this->hours->compute() * 1.1;
    }
}
