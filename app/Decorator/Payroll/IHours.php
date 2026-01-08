<?php

namespace App\Decorator\Payroll;

interface IHours
{
    //
    public function getHoursType();
    public function getPayType();
    public function getDayType();
    public function compute();
}
