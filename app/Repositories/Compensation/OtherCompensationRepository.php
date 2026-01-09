<?php

namespace App\Repositories\Compensation;

use Illuminate\Support\Facades\DB;

class OtherCompensationRepository
{
    public function getCompensation($employee,$period)
    {
        return DB::table('compensation_other_headers')
                ->join('compensation_other_details','compensation_other_headers.id','=','header_id')
                ->where('compensation_other_details.biometric_id',$employee->biometric_id)
                ->where([
                    ['period_id','=',$period->id],
                    ['total_amount','>',0]
                ])
                ->get();
    }
}