<?php

namespace App\Decorator\Payroll;

class OvertimeDecorator extends HoursDecorator implements IHours
{

    public function compute()
    {
        return ($this->getDayType() == 'REGULARDAY') ?  $this->hours->compute() * 1.25  :  $this->hours->compute() * 1.3; 
    }
}
