<?php

namespace App\Builder;

interface IPayregEmployee
{
    //
    public function computeLate();

    public function computeUnderTime();

    public function computeAbsence();

    public function computeBasicPay();

    // public function computeOverTime();

}
