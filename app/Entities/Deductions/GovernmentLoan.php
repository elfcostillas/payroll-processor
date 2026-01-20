<?php

namespace App\Entities\Deductions;

use App\Repositories\Deductions\GovernmentLoanRepository;

class GovernmentLoan implements Installments
{
    protected $props;


    public function __construct($props)
    {
       $this->props = $props;
    }

    public function getPostedPayments()
    {
        $repo = app(GovernmentLoanRepository::class);

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
//total_amount ammortization
