<?php

namespace App\Services\Compensation;

use App\Repositories\Compensation\FixedCompensationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FixedCompensationService
{
    private $user;
    
    public function __construct(protected FixedCompensationRepository $fixedCompensationRepo)
    {
       
    }

    public function run($employee,$period,$user_id)
    {
        $tmp_earn = [];
        $fixed_compensation = $this->fixedCompensationRepo->getCompensation($employee,$period);
        
        foreach($fixed_compensation as $earn)
        {
            $tmp = [
                'period_id' => $period->id,
                'biometric_id' => $employee->biometric_id,
                'compensation_type' => $earn->compensation_type,
                'amount' => $earn->total_amount,
                'deduction_id' => $earn->id,
                'emp_level' => 'non-confi',
                'user_id' => $user_id
            ];

            array_push($tmp_earn,$tmp);
        }

        DB::table('unposted_fixed_compensations')->insertOrIgnore($tmp_earn);
    }




}

