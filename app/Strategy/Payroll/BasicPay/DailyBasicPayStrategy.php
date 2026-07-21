<?php

namespace App\Strategy\Payroll\BasicPay;

class DailyBasicPayStrategy implements IBasicPay
{
    public function compute($payregObject)
    {
        
        $basic_pay = round($payregObject['basic_salary'] * $payregObject['ndays']);

        return $basic_pay - $payregObject['late_eq_amount'] - $payregObject['under_time_amount'];
    }
}
