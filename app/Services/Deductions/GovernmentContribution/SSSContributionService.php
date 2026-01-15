<?php

namespace App\Services\Deductions\GovernmentContribution;

use Illuminate\Support\Facades\DB;

class SSSContributionService
{
    public function __construct()
    {
        
    }

    public function compute($period,$employee)
    {
        if($period->cut_off == 2 &&  $employee->getField('deduct_sss') =='Y' && ($employee->getField('sss_no') !='' && !is_null($employee->getField('sss_no'))))
        {
            $salary_credit = (float) $this->getSalaryCredit($employee);
            $row = $this->getSSSrow($salary_credit);
            if($row){
                return [
                    'sss_prem' => (float) $row->ee_share,
                    'sss_wisp' => (float) $row->mpf_ee,
                ];
            }else{
                return [
                    'sss_prem' => 0,
                    'sss_wisp' => 0,
                ];
            }
        }else{
            return [
                'sss_prem' => 0,
                'sss_wisp' => 0,
            ];
        }
    }

    public function getSalaryCredit($employee)
    {
        return ($employee->getField('pay_type') == 1) ? $employee->getField('basic_salary') : $employee->getField('basic_salary') * 26;
    }

    public function getSSSrow($salary_credit)
    {
            return DB::table('hris_sss_table_2025')->select('ee_share','mpf_ee')
                ->whereRaw($salary_credit." between range1 and range2")
                ->first();
    }
}

