<?php

namespace App\Strategy\Payroll\DailyRate;

use App\Strategy\Payroll\DailyRate\IDailyRateStrategy;

class ComputeDailyRateSemiMonthlyStrategy implements IDailyRateStrategy
{
    public function compute($basicPay)
    {
        return round(($basicPay * 12) / 313,2);
    }
}
