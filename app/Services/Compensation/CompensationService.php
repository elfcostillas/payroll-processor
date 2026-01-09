<?php

namespace App\Services\Compensation;

use Illuminate\Support\Facades\DB;

class CompensationService
{
    //

    public function getTotal($periodObj,$employeeObj,$user_id)
    {
        $total_compenstaion = 0;
        $tables = ['unposted_fixed_compensations','unposted_other_compensations'];

        foreach($tables as $table){
            $comp = DB::table($table)->select(DB::raw("ifnull(sum(amount),0) as amount"))
                    ->where('period_id','=',$periodObj->id)
                    ->where('biometric_id','=',$employeeObj->getBioId())
                    ->first();
                    
            $total_compenstaion += (float) $comp->amount;
        }

        return $total_compenstaion;
    }
}
