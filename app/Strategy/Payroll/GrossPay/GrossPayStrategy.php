<?php

namespace App\Strategy\Payroll\GrossPay;

class GrossPayStrategy implements IGrossPay
{
    //
    public function compute($payregObject)
    {
        return $payregObject['basic_pay']   
                        + $payregObject['vl_wpay_amount']
                        + $payregObject['semi_monthly_allowance']
                        + $payregObject['daily_allowance']
                        + $payregObject['sl_wpay_amount']
                        + $payregObject['bl_wpay_amount']
                        + $payregObject['brv_amount']
                        + $payregObject['svl_amount']
                        + $payregObject['reg_ot_amount']
                        + $payregObject['reg_nd_amount']
                        + $payregObject['reg_ndot_amount']
                        + $payregObject['rd_hrs_amount']
                        + $payregObject['rd_ot_amount']
                        + $payregObject['rd_ndot_amount']
                        + $payregObject['leghol_count_amount']
                        + $payregObject['leghol_hrs_amount']
                        + $payregObject['leghol_ot_amount']
                        + $payregObject['leghol_nd_amount']
                        + $payregObject['leghol_rd_amount']
                        + $payregObject['leghol_rdot_amount']
                        + $payregObject['leghol_ndot_amount']
                        + $payregObject['leghol_rdnd_amount']
                        + $payregObject['leghol_rdndot_amount']
                        + $payregObject['sphol_count_amount']
                        + $payregObject['sphol_hrs_amount']
                        + $payregObject['sphol_ot_amount']
                        + $payregObject['sphol_nd_amount']
                        + $payregObject['sphol_rd_amount']
                        + $payregObject['sphol_rdot_amount']
                        + $payregObject['sphol_ndot_amount']
                        + $payregObject['sphol_rdnd_amount']
                        + $payregObject['sphol_rdndot_amount'];                      ;
    }
}

