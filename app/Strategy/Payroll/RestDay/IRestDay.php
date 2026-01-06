<?php

namespace App\Strategy\Payroll\RestDay;

interface IRestDay
{
    public function compute($dailyRate,$hours);
}
