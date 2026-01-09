<?php

namespace App\Factory\Payroll;


use App\Strategy\Payroll\BasicPay\DailyBasicPayStrategy;
use App\Strategy\Payroll\BasicPay\IBasicPay;

use App\Strategy\Payroll\BasicPay\SemiMonthlyBasicPayStrategy;

class BasicPayStrategyFactory
{
    public static function getStrategy($payType) : IBasicPay
    {
        return match($payType){
            1 => new SemiMonthlyBasicPayStrategy(),
            2 => new DailyBasicPayStrategy(),
        };
    }
}
