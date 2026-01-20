<?php

namespace App\Entities\Deductions;

interface Installments
{
    public function getPostedPayments();

    public function getRemainingBalance();
}
