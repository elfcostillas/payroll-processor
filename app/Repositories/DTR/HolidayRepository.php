<?php

namespace App\Repositories\DTR;

use Illuminate\Support\Facades\DB;

class HolidayRepository
{
    //

    public function getHolidayCounts($biometric_id,$period_id)
    {
        $qry = "SELECT holiday_date,holiday_type FROM holidays INNER JOIN holiday_location ON holidays.id = holiday_id
        INNER JOIN payroll_period ON holiday_date BETWEEN date_from AND date_to
        INNER JOIN employees ON employees.location_id = holiday_location.location_id
        INNER JOIN holiday_types ON holiday_types.id = holiday_type
        WHERE payroll_period.id = $period_id
        AND biometric_id = $biometric_id";

        $result = DB::select($qry);

        return $result;
    }
}
