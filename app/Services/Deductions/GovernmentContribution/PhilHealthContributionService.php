<?php

namespace App\Services\Deductions\GovernmentContribution;

class PhilHealthContributionService
{
    //

    protected $rate = 5;

    public function compute($period,$employee)
    {
        if(($period->cut_off == 2) && $employee->getField('deduct_phic') == 'Y' && ($employee->getField('phic_no') != '' && !is_null($employee->getField('phic_no')))){
            $rate = $this->rate/100;
            $salary_credit = (float) $this->getSalaryCredit($employee);

            return ($salary_credit>=100000.00) ? round((100000.00 * $rate)/2,2) : round(($salary_credit * $rate)/2,2);
        }else{
            return 0.0;
        }
    }

    public function getSalaryCredit($employee)
    {
        return ($employee->getField('pay_type') == 1) ? $employee->getField('basic_salary') : $employee->getField('basic_salary') * 26;
    }
}