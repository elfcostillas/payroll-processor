<?php

namespace App\Repositories\Deductions;

use Illuminate\Support\Facades\DB;

class FixedDeductionRepository
{


    public function getFixedDeduction($employee,$period)
    {
        return DB::table('deduction_fixed')
                ->where('is_stopped','=','N')
                ->where('amount','>',0)
                ->where('deduction_fixed.biometric_id','=',$employee->getField('biometric_id'))
                ->select('amount','deduction_type','biometric_id')
                ->get();
    }

    public function getTotalUnposted($employee,$period,$user_id)
    {
      
        return DB::table('unposted_fixed_deductions')
            ->where('period_id','=',$period->id)
            ->where('biometric_id','=',$employee->getField('biometric_id'))
            ->where('user_id','=',$user_id)
            ->select(DB::raw("ifnull(SUM(amount),0.00) as amount"))
            ->first();
    }
}

// SELECT amount,deduction_type,biometric_id 
// FROM deduction_fixed WHERE is_stopped = 'N' AND amount > 0 AND biometric_id = 847;