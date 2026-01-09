<?php

namespace App\Factory\Payroll;

use App\Strategy\Payroll\GrossPay\GrossPayStrategy;
use App\Strategy\Payroll\GrossPay\IGrossPay;

class GrossPayStrategyFactory
{
    public static function getStrategy($payType) : IGrossPay
    {
        return match($payType){
            1 => new GrossPayStrategy(),
            2 => new GrossPayStrategy(),
        };
    }
}
