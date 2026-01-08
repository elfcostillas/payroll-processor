<?php

namespace App\Decorator\Payroll;

class SpecialHolidayDecorator extends HoursDecorator implements IHours
{
    //
    public function compute()
    {
        
        if($this->getPayType() == 1){

            if($this->getDayType() == 'HOLIDAY' && $this->getHoursType() == 'REGULARTIME'){
                return  $this->hours->compute() * 0.3;
            }

            if($this->getDayType() == 'HOLIDAY' && $this->getHoursType() == 'OVERTIME'){
                return  $this->hours->compute() * 1.3;
            }
        
            return  $this->hours->compute() * 1.5; // NIGHSHIFT + HOLIDAY 

        }else{
            return $this->hours->compute() * 1.3;
        }
    }

}
