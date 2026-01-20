<?php

namespace App\Repositories\Deductions;
use Illuminate\Support\Facades\DB;

class InstallmentsRepository
{
    public function getLoans($employee,$period)
    {
        
        return DB::table('deduction_installments')
                ->where('biometric_id','=',$employee->getField('biometric_id'))
                ->where('period_id','<=',$period->id)
                ->get();
    }

    public function getPostedPayments($id)
    {
        return DB::table('posted_installments')->where('deduction_id','=',$id)
                ->select(DB::raw("ifnull(SUM(amount),0.00) as amount"))
                ->first();
    }

    public function getTotalUnposted($employee,$period,$user_id)
    {
        return DB::table('unposted_installments')
            ->where('period_id','=',$period->id)
            ->where('biometric_id','=',$employee->getField('biometric_id'))
            ->where('user_id','=',$user_id)
            ->select(DB::raw("ifnull(SUM(amount),0.00) as amount"))
            ->first();
    }
}
