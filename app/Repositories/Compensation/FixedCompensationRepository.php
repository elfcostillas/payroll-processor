<?php

namespace App\Repositories\Compensation;

use Illuminate\Support\Facades\DB;

class FixedCompensationRepository
{
    //

    public function getCompensation($employee,$period)
    {
        return DB::table('compensation_fixed_headers')
                ->join('compensation_fixed_details','compensation_fixed_headers.id','=','header_id')
                ->where('compensation_fixed_details.biometric_id',$employee->biometric_id)
                ->where([
                    ['period_id','=',$period->id],
                    ['total_amount','>',0]
                ])
                ->get();
    }
}
