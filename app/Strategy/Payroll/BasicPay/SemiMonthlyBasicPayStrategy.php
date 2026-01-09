<?php

namespace App\Strategy\Payroll\BasicPay;

class SemiMonthlyBasicPayStrategy implements IBasicPay
{
    //
    public function compute($payregObject)
    {
        $basic_pay = round($payregObject['basic_salary']/2,2);

        return $basic_pay - $payregObject['late_eq_amount']
                          - $payregObject['under_time_amount']
                          - $payregObject['absences_amount']

                        - $payregObject['vl_wpay_amount']
                        - $payregObject['sl_wpay_amount']
                        - $payregObject['bl_wpay_amount']
                        - $payregObject['brv_amount']
                        - $payregObject['svl_amount']

                        - round($payregObject['daily_rate'] * $payregObject['actual_dblsphol'],2)
                        - round($payregObject['daily_rate'] * $payregObject['actual_dblhol'],2)
                        - round($payregObject['daily_rate'] * $payregObject['actual_sphol'],2)
                        - round($payregObject['daily_rate'] * $payregObject['actual_reghol'],2);

    }
}
