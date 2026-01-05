<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NonConfiService;
use App\Services\ConfiService;
use App\Repositories\PayrollPeriod\PayrollPeriodRepository;


class ProcessController extends Controller
{
    //
    public function __construct(protected NonConfiService $nonConfiService, protected ConfiService $confiService,protected PayrollPeriodRepository $payrollPeriodRepository) {
       
    }

    public function process($employee_type, $period_id) {

        $period = $this->payrollPeriodRepository->find($period_id);

        $service = match ($employee_type) {
            'confi' => $this->confiService,
            'non-confi' => $this->nonConfiService
        };    
        
        if($period) {
            $service->process($period);
        }
        
    }

}
