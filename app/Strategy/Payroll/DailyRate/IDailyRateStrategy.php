<?php

namespace App\Strategy\Payroll\DailyRate;

interface IDailyRateStrategy
{
    public function compute($basicPay);
}
