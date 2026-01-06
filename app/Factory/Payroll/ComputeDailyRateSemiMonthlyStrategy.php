<?php

namespace App\Factory\Payroll\DailyRate;
use App\Factory\Payroll\DailyRateStrategy;

class ComputeDailyRateSemiMonthlyStrategy implements DailyRateStrategy
{
    public function compute($basicPay)
    {
        return round(($basicPay * 12) / 313,2);
    }
}
