<?php

namespace App\Decorator\Payroll;

interface IHours
{
    //
    public function getType();
    public function getPayType();
    public function compute();
}
