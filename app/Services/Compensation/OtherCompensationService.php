<?php

namespace App\Services\Compensation;

use App\Repositories\Compensation\OtherCompensationRepository;
use Illuminate\Support\Facades\DB;

class OtherCompensationService
{
    public function __construct(protected OtherCompensationRepository $otherCompensationRepo)
    {
     
    }

    public function run($employee,$period,$user_id)
    {
        $tmp_earn = [];
        $other_compensation = $this->otherCompensationRepo->getCompensation($employee,$period);
       
        foreach($other_compensation as $earn)
        {
            $tmp = [
                'period_id' => $period->id,
                'biometric_id' => $earn->biometric_id,
                'compensation_type' => $earn->compensation_type,
                'amount' => $earn->total_amount,
                'deduction_id' => $earn->id,
                'emp_level' => 'non-confi',
                'user_id' => $user_id
            ];

            array_push($tmp_earn,$tmp);
        }
        
        DB::table('unposted_other_compensations')->insertOrIgnore($tmp_earn);
    }
        
}
