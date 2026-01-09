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

    // public function process($employee_type, $period_id, $user_id) {
    public function process(Request $request) {

        $user_id = $request->user_id;

        $period = $this->payrollPeriodRepository->find($request->period_id);
      
        $service = match ($request->employee_type) {
            'confi' => $this->confiService,
            'non-confi' => $this->nonConfiService
        };    
        
        if($period) {
            $service->process($period,$user_id);
        }
        
    }

}
