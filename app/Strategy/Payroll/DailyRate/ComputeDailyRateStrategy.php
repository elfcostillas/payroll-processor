<?php

namespace App\Strategy\Payroll\DailyRate;
use App\Strategy\Payroll\DailyRate\IDailyRateStrategy;

class ComputeDailyRateStrategy implements IDailyRateStrategy
{
    
    public function compute($basicPay)
    {
        return $basicPay;
    }
}
