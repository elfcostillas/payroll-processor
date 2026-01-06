<?php

namespace App\Strategy\Payroll\RestDay;

class SemiMonthlyPaidStrategy implements IRestDay
{
    //
    public function compute($hourlyRate,$hours)
    {
        return round($hourlyRate * 0.3 * $hours,2);
    }
}
