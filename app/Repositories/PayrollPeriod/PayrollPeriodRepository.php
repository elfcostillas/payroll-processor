<?php

namespace App\Repositories\PayrollPeriod;

use Illuminate\Support\Facades\DB;

class PayrollPeriodRepository
{
    
    public function find($id) {
        return DB::table('payroll_period')->find($id);
    }
}
