<?php

namespace App\Services\Deductions\CompanyDeductions;

use App\Entities\Deductions\GovernmentLoan;
use App\Repositories\Deductions\GovernmentLoanRepository;
use Illuminate\Support\Facades\DB;

class GovernmentLoansService
{
    public function __construct(protected GovernmentLoanRepository $gov_loan_repo)
    {

    }

    public function run($employee,$period,$user_id)
    {
        $loans = $this->gov_loan_repo->getLoans($employee,$period);

        $tmp_loans = [];
        
        foreach($loans as $loan)
        {
            $loanObject = new GovernmentLoan($loan);
  
            $array = array(
                'period_id' => $period->id,
                'biometric_id' => $employee->getField('biometric_id'),
                'deduction_type' => $loan->deduction_type,
                'amount' => $loanObject->getAmmortization(),
                'deduction_id' => $loan->id,
                'emp_level' =>  ($employee->getField('emp_level') < 5) ? 'confi' : 'non-confi',
                'user_id' => $user_id,
            );

            array_push($tmp_loans,$array);
        }

        DB::table('unposted_loans')->insert($tmp_loans);
    }

    public function getTotalLoan($employee,$period,$user_id)
    {
        return $this->gov_loan_repo->getTotalUnposted($employee,$period,$user_id);
    }
}
