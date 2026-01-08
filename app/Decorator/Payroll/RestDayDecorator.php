<?php

namespace App\Decorator\Payroll;

class RestDayDecorator extends HoursDecorator implements IHours
{

    public function compute()
    {
        /*
        * 1 = Semi Monthly
        * 2 = Daily
        */
        if($this->getPayType() == 1){
            // return ($this->getDayType() == 'RESTDAY' && $this->getHoursType() == 'REGULARTIME') ? $this->hours->compute() * 0.3 : $this->hours->compute() * 1.3;

            if($this->getDayType() == 'RESTDAY' && $this->getHoursType() == 'REGULARTIME'){
                return  $this->hours->compute() * 0.3;
            }

            if($this->getDayType() == 'HOLIDAY' && $this->getHoursType() == 'REGULARTIME'){
                return  $this->hours->compute() * 1.6;
            }

            if($this->getDayType() == 'HOLIDAY' && $this->getHoursType() == 'OVERTIME'){
                return  $this->hours->compute() * 2.6;
            }
        
            return  $this->hours->compute() * 1.3;

        }else{
            return $this->hours->compute() * 1.3;
        }
    }
}
