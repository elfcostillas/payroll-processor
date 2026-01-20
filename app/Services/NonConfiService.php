<?php

namespace App\Services;

use App\Builder\PayregEmployeeBuilder;
use App\Entities\Employee\Employee;
use App\Factory\Payroll\BasicPayStrategyFactory;
use App\Factory\Payroll\DailyRateStrategyFactory;
use App\Factory\Payroll\GrossPayStrategyFactory;
use App\Factory\Payroll\RestDayStrategyFactory;
use App\Repositories\DTR\DailyTimeRecordRepository;
use App\Repositories\Employee\NonConfiRepository;
use App\Repositories\PayrollRegister\PayrollRegisterRepository;
use App\Services\Compensation\CompensationService;
use App\Services\Compensation\FixedCompensationService;
use App\Services\Compensation\OtherCompensationService;
use App\Services\Deductions\CompanyDeductions\FixedDeductionService;
use App\Services\Deductions\CompanyDeductions\GovernmentLoansService;
use App\Services\Deductions\CompanyDeductions\InstallmentsService;
use App\Services\Deductions\CompanyDeductions\OneTimeDeductionService;
use App\Services\Deductions\GovernmentContribution\GovernmentContributionService;
use Illuminate\Support\Facades\DB;

class NonConfiService
{
    //

    public function __construct(
        protected NonConfiRepository $nonConfiRepository,
        protected PayrollRegisterRepository $payrollRegisterRepository,
        protected DailyTimeRecordRepository $dailyTimeRecordRepository,
        protected FixedCompensationService $fixedCompensationService,
        protected OtherCompensationService $otherCompensationService,
        protected CompensationService $compensationService,
        protected GovernmentContributionService $gov_contri_service,
        protected OneTimeDeductionService $otd_service,
        protected InstallmentsService $installments_service,
        protected GovernmentLoansService $government_loans_service,
        protected FixedDeductionService $fixed_deduction_service
        
        ) {
    }

    function process($period,$user_id) {
        
        $employees = $this->nonConfiRepository->getAllWithDTR($period);

        $schema = $this->payrollRegisterRepository->getSchema('unposted');
        $this->clearTemporaryData($period,$user_id);

        $generated_on = now();

        foreach($employees as $employee) {
            $employeeObj = new Employee($this->nonConfiRepository->find($employee->biometric_id));
            $dailyRateStrategy = DailyRateStrategyFactory::getStrategy($employee->pay_type);
            $builder = new PayregEmployeeBuilder();
            $basicPayStrategy = BasicPayStrategyFactory::getStrategy($employee->pay_type);
            $grossPayStrategy = GrossPayStrategyFactory::getStrategy($employee->pay_type);
           
            $this->fixedCompensationService->run($employee,$period,$user_id);    
            $this->otherCompensationService->run($employee,$period,$user_id); 
          
            $this->otd_service->run($employeeObj,$period,$user_id);
            $this->fixed_deduction_service->run($employeeObj,$period,$user_id);
            $this->government_loans_service->run($employeeObj,$period,$user_id);
            $this->installments_service->run($employeeObj,$period,$user_id);

           
            $dtr = $this->dailyTimeRecordRepository->getDTRByPeriodAndEmployee($period,$employee);
            // $restDayStrategy = RestDayStrategyFactory::getStrategy($employee->pay_type);

            $builder->setEmployee($employeeObj)
                    ->setDailyRateStrategy($dailyRateStrategy)
                    ->setEmployeeDailyTimeRecord($dtr)
                    ->setSchema($schema)
                    ->setRates()
                    ->setPeriod($period)
                    ->setFields($generated_on,$user_id)
                    ->computeLateAmount()
                    ->computeUnderTimeAmount()
                    ->computeVacationLeaveAmount()
                    ->computeSickLeaveAmount()
                    ->computeBirthdayLeaveAmount()
                    ->computeBereavementLeaveAmount()
                    ->computeAbsenceAmount()
                    ->computeReglarOverTimeAmount()
                    ->computeRegularNightDifferentialAmount()
                    ->computeRestDayAmount()
                    ->computeLegalHolidayHrsAndPremiumAmount()
                    ->computeSpecialHolidayAndPremumAmount()

                    ->computeBasicPay($basicPayStrategy)
                    ->computeGrossPay($grossPayStrategy)
                    ->computeGrossTotal($this->compensationService,$user_id)
                    ->computeGovernmentContributions($this->gov_contri_service)
                    ->computeOneTimeDeduction($this->otd_service,$user_id)
                    ->computeFixedDeduction($this->fixed_deduction_service,$user_id)
                    ->computeGovernmentLoans($this->government_loans_service,$user_id)
                    ->computeOfficeInstallment($this->installments_service,$user_id)
                    ->getTotalDeduction()
                    ->getNetPay()
                    ->getFields()
                   
                    // ->computeSVLAmount()
                    
                    
                    ;


            /*
                make factory for strategy for ComputeDailyRate Daily / MOnhtly
            */
            
            
        }
    } 

    public function clearTemporaryData($period,$user_id)
    {
        $employees = DB::table('employees')
                    ->where('emp_level',5)
                    ->where('exit_status',1)
                    ->select('biometric_id');

        $tables = array(
            'payrollregister_unposted_s',

            'unposted_onetime_deductions',
            'unposted_installments',
            'unposted_fixed_deductions',
            'unposted_loans',

            'unposted_fixed_compensations',
            'unposted_other_compensations',
        );

        foreach($tables as $key => $value)
        {
            $count = DB::table($value)->where('period_id',$period->id)
            ->whereIn('biometric_id',$employees->pluck('biometric_id'))
            ->delete();
        }
    }

}
