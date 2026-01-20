<?php

namespace App\Services\Deductions\GovernmentContribution;

use Illuminate\Support\Facades\DB;

class WithHoldingTaxService
{
    public function index()
    {

    }

    public function compute($period,$employee)
    {
       
        if($employee->getField('deduct_wtax') == 'Y'){
            if($employee->getField('manual_wtax')!= "" && !is_null($employee->getField('manual_wtax'))){
                return (float) $employee->getField('manual_wtax');
            }else{
                    $salary_credit = (float) $this->getSalaryCredit($employee);
                    $annual = $salary_credit * 12;
                return $this->getTaxValue($annual);
            }
        }else{
            return 0;
        }
    }

    public function getSalaryCredit($employee)
    {
        return ($employee->getField('pay_type') == 1) ? $employee->getField('basic_salary') : $employee->getField('basic_salary') * 26;
    }

    public function getTaxValue($annual)
    {
        $row = $range = DB::table('wtax')
                ->whereRaw(" $annual between range1 and range2 ")
                ->where('pay_type','=',2)
                ->first();

        if($row){
            return  round($row->fix + (($annual - $row->range1) <= 0 ? 0  : $annual - $row->range1) *  $row->percentage/24,2);
        }else{
            return 0.0;
        }
    }
}
