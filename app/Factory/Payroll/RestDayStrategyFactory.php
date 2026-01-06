<?php

namespace App\Factory\Payroll;

use App\Strategy\Payroll\RestDay\DailyPaidStrategy;
use App\Strategy\Payroll\RestDay\IRestDay;
use App\Strategy\Payroll\RestDay\SemiMonthlyPaidStrategy;

class RestDayStrategyFactory
{
    public static function getStrategy($payType) : IRestDay
    {
        return match($payType){
            1 => new SemiMonthlyPaidStrategy(),
            2 => new DailyPaidStrategy(),
        };
    }
}
