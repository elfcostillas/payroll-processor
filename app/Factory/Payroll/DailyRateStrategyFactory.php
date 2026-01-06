<?php

namespace App\Factory\Payroll;


use App\Strategy\Payroll\DailyRate\ComputeDailyRateSemiMonthlyStrategy;
use App\Strategy\Payroll\DailyRate\ComputeDailyRateStrategy;
use App\Strategy\Payroll\DailyRate\IDailyRateStrategy;

class DailyRateStrategyFactory
{
    public static function getStrategy($payType) : IDailyRateStrategy
    {
        return match($payType){
            1 => new ComputeDailyRateSemiMonthlyStrategy(),
            2 => new ComputeDailyRateStrategy(),
        };
    }
}
