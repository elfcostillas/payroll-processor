<?php

namespace App\Decorator\Payroll;

class LegalHolidayDecorator extends HoursDecorator implements IHours
{

    public function compute()
    {
        
        if($this->getPayType() == 1){
            return ($this->getDayType() == 'HOLIDAY' && $this->getHoursType() == 'REGULARTIME') ? $this->hours->compute() * 1.0 : $this->hours->compute() * 2.0;  
        }else{
            return  $this->hours->compute() * 2;
        }
    }
}
