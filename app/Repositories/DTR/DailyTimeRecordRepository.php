<?php

namespace App\Repositories\DTR;

use Illuminate\Support\Facades\DB;

class DailyTimeRecordRepository
{
    //

    public function getDTRByPeriodAndEmployee($period,$employee)
    {
        return DB::table('edtr_totals')
                ->where('period_id','=',$period->id)
                ->where('biometric_id','=',$employee->biometric_id)
                ->first();
    }
}
