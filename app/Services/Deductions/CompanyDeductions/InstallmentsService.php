<?php

namespace App\Services\Deductions\CompanyDeductions;

use App\Entities\Deductions\OfficeInstallments;
use App\Repositories\Deductions\InstallmentsRepository;
use Illuminate\Support\Facades\DB;


class InstallmentsService
{
    public function __construct(protected InstallmentsRepository $installmentsRepository)
    {
        
    }

    public function run($employee,$period,$user_id)
    {
        $loans = $this->installmentsRepository->getLoans($employee,$period);

        $tmp_loans = [];
        
        foreach($loans as $loan)
        {
            $loanObject = new OfficeInstallments($loan);
  
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

        DB::table('unposted_installments')->insert($tmp_loans);
    }

    public function getTotalLoan($employee,$period,$user_id)
    {
        return $this->installmentsRepository->getTotalUnposted($employee,$period,$user_id);
    }
}
