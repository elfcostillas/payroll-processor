<?php

namespace App\Services\Deductions\CompanyDeductions;

use App\Repositories\Deductions\OneTimeDeductionRepository;
use Illuminate\Support\Facades\DB;

class OneTimeDeductionService
{
    public function __construct(protected OneTimeDeductionRepository $otd_repo)
    {
       
    }

    public function run($employee,$period,$user_id)
    {
        $otds = $this->otd_repo->getAssortedOneTimeDeduction($employee,$period);
        $otd_arr = [];

        if($otds)
        {
            foreach($otds as $otd)
            {
               
                $otd_tmp = (array) $otd;
                $otd_tmp['user_id'] = (int) $user_id;
                $otd_tmp['emp_level'] = ($employee->getField('emp_level') < 5) ? 'confi' : 'non-confi';

                array_push($otd_arr,$otd_tmp);
            }

            DB::table('unposted_onetime_deductions')->insert($otd_arr);
        }
    }

    public function getTotalLoan($employee,$period,$user_id)
    {
        return $this->otd_repo->getTotalUnposted($employee,$period,$user_id);
    }
}
