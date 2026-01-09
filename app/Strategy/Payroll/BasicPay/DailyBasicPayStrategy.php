<?php

namespace App\Strategy\Payroll\BasicPay;

class DailyBasicPayStrategy implements IBasicPay
{
    public function compute($payregObject)
    {
        dd('basic',$payregObject);
    }
}
