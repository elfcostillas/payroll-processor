<?php

namespace App\Strategy\Payroll\RestDay;

class DailyPaidStrategy implements IRestDay
{
    //
    public function compute($hourlyRate,$hours)
    {
        return round($hourlyRate * 1.3 * $hours,2);
    }
}
