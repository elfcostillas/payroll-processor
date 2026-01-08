<?php

namespace App\Decorator\Payroll;

class Hours implements IHours
{
    //
    protected $hours_type;
    protected $number_of_hours;
    protected $hourly_rate;
    protected $pay_type;
    protected $day_type;

    public function __construct($number_of_hours,$hourly_rate,$hours_type,$pay_type,$day_type)
    {
        $this->number_of_hours = $number_of_hours;
        $this->hourly_rate = $hourly_rate;
        $this->hours_type = $hours_type;
        $this->day_type = $day_type;
        $this->pay_type = $pay_type; // emlpoyee pay type | 1 = semi monthly ; 2 = Daily 

    }

   
    public function getPayType()
    {
        return $this->pay_type;
    }

    public function getHoursType() /* TARGET or INTENT of the computation */
    {
        return $this->hours_type;
    }

    public function getDayType()
    {
        return $this->day_type;
    }

    public function compute()
    {
        return  $this->number_of_hours * $this->hourly_rate;
    }
}
