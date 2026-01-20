<?php

namespace App\Repositories\Deductions;

use Illuminate\Support\Facades\DB;

class OneTimeDeductionRepository
{
    
    public function getAssortedOneTimeDeduction($employee,$period)
    {

        return DB::table('deduction_onetime_headers')
                ->join('deduction_onetime_details','deduction_onetime_headers.id','=','deduction_onetime_details.header_id')
                ->where('deduction_onetime_headers.period_id','=',$period->id)
                ->where('deduction_onetime_details.biometric_id','=',$employee->getField('biometric_id'))
                ->select('deduction_onetime_headers.id as deduction_id','period_id','biometric_id','deduction_type','amount')
                ->get();
    }

    public function getTotalUnposted($employee,$period,$user_id)
    {
      
        return DB::table('unposted_onetime_deductions')
            ->where('period_id','=',$period->id)
            ->where('biometric_id','=',$employee->getField('biometric_id'))
            ->where('user_id','=',$user_id)
            ->select(DB::raw("ifnull(SUM(amount),0.00) as amount"))
            ->first();
    }
}


/*

select ifnull(SUM(amount),0.00) as amount 
from unposted_onetime_deductions 
where period_id = 74 and biometric_id = 0 and user_id = 1

SELECT period_id,biometric_id,deduction_type,amount 
FROM deduction_onetime_headers 
INNER JOIN deduction_onetime_details ON deduction_onetime_headers.id = deduction_onetime_details.header_id
WHERE deduction_onetime_headers.period_id = 74
AND amount > 0
AND biometric_id = 126

*/