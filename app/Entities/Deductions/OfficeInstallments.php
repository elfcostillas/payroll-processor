<?php

namespace App\Entities\Deductions;

use App\Repositories\Deductions\InstallmentsRepository;

class OfficeInstallments
{
    protected $props;


    public function __construct($props)
    {
       $this->props = $props;
    }

    public function getPostedPayments()
    {
        $repo = app(InstallmentsRepository::class);

        $payments = $repo->getPostedPayments($this->props->id);

        return $payments->amount;
    }

    public function getRemainingBalance()
    {
        $payments = (float) $this->getPostedPayments();
        return $this->props->total_amount - $payments;
        
    }

    public function getAmmortization()
    {
        $balance = $this->getRemainingBalance();

        return ($balance > $this->props->ammortization) ? (float) $this->props->ammortization : (float) $balance;
    }
}
