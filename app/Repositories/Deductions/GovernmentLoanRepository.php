<?php

namespace App\Repositories\Deductions;

use Illuminate\Support\Facades\DB;

class GovernmentLoanRepository
{
    public function getLoans($employee,$period)
    {
        
        return DB::table('deduction_gov_loans')
                ->where('biometric_id','=',$employee->getField('biometric_id'))
                ->where('period_id','<=',$period->id)
                ->get();
    }

    public function getPostedPayments($id)
    {
        return DB::table('posted_loans')->where('deduction_id','=',$id)
                ->select(DB::raw("ifnull(SUM(amount),0.00) as amount"))
                ->first();
    }

    public function getTotalUnposted($employee,$period,$user_id)
    {
        return DB::table('unposted_loans')
            ->where('period_id','=',$period->id)
            ->where('biometric_id','=',$employee->getField('biometric_id'))
            ->where('user_id','=',$user_id)
            ->select(DB::raw("ifnull(SUM(amount),0.00) as amount"))
            ->first();
    }
}


/*

select * from posted_loans where deduction_id = 


SELECT * FROM deduction_gov_loans 
WHERE biometric_id = 847 AND is_stopped = 'N' AND period_id <= 74;
*/