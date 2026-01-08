<?php

namespace App\Decorator\Payroll;

class SpecialHolidayRestDayDecorator extends HoursDecorator implements IHours
{
    public function compute()
    {
        if($this->getPayType() == 1){
            if($this->getHoursType() == 'REGULARTIME'){
                return  $this->hours->compute() * 0.5;
            }
                return $this->hours->compute() * 1.5; 
        }else{  
            return $this->hours->compute() * 1.5;
        }
    }
}
