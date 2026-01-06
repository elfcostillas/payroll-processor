<?php

namespace App\Decorator\Payroll;

class Hours implements IHours
{
    //
    protected $hours_type;
    protected $hours;
    protected $hourly_rate;
    protected $pay_type;

    public function __construct($hours,$hourly_rate,$hours_type,$pay_type)
    {
       $this->hours = $hours;
       $this->hours_type = $hours_type;
       $this->hourly_rate = $hourly_rate;
       $this->pay_type = $pay_type;

    }

   
    public function getPayType()
    {
        return $this->pay_type;
    }


    public function getType()
    {
        return $this->hours_type;
    }

    public function compute()
    {
        return  $this->hours * $this->hourly_rate;
    }
}
