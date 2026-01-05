<?php

namespace App\Services;

use App\Builder\PayregEmployeeBuilder;
use App\Entities\Employee\Employee;
use App\Repositories\Employee\NonConfiRepository;
use App\Repositories\PayrollRegister\PayrollRegisterRepository;

class NonConfiService
{
    //

    public function __construct(protected NonConfiRepository $nonConfiRepository,protected PayrollRegisterRepository $payrollRegisterRepository) {
      
    }

    function process($period) {
        
        $employees = $this->nonConfiRepository->getAllWithDTR($period);

        $schema = $this->payrollRegisterRepository->getSchema('unposted');

        foreach($employees as $employee) {

            $employeeObj = new Employee($this->nonConfiRepository->find($employee->biometric_id));

            $builder = new PayregEmployeeBuilder();

            $builder->setEmployee($employeeObj)
                    ->setSchema($schema);


            /*
                make factory for strategy for ComputeDailyRate Daily / MOnhtly
            */
            
            
        }
    } 

}
