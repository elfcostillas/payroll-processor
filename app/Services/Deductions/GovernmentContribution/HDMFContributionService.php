<?php

namespace App\Services\Deductions\GovernmentContribution;

class HDMFContributionService
{
    public function compute($period,$employee)
    {
        if($period->cut_off == 1){
            return ($employee->getField('hdmf_no') != '' && !is_null($employee->getField('hdmf_no'))) ? (float) $employee->getField('hdmf_contri') : 0.00 ;
        }else{  
            return 0;
        }

    }
}
