<?php

namespace App\Services\Deductions\CompanyDeductions;

use App\Repositories\Deductions\FixedDeductionRepository;
use Illuminate\Support\Facades\DB;

class FixedDeductionService
{
    public function __construct(protected FixedDeductionRepository $fixed_deduction_repository)
    {
        
    }

    public function run($employee,$period,$user_id)
    {
        $fixed_deds = $this->fixed_deduction_repository->getFixedDeduction($employee,$period);
        $fixed_ded_arr = [];

        foreach($fixed_deds as $fixed_ded)
        {
            $arr = (array) $fixed_ded;
            $arr['period_id'] = $period->id;
            $arr['user_id'] = $user_id;
            $arr['emp_level'] = ($employee->getField('emp_level') < 5) ? 'confi' : 'non-confi';

            array_push($fixed_ded_arr,$arr);
        }

        DB::table('unposted_fixed_deductions')->insert($fixed_ded_arr);
    }

    public function getTotalLoan($employee,$period,$user_id)
    {
        return $this->fixed_deduction_repository->getTotalUnposted($employee,$period,$user_id);
    }
}
