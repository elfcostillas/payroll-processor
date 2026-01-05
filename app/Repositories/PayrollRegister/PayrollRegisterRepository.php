<?php

namespace App\Repositories\PayrollRegister;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PayrollRegisterRepository
{
    //
    public function getSchema($payrollStatus)
    {
        return match($payrollStatus) {
            'posted' => Schema::getColumnListing('payrollregister_posted_s'),
            'unposted' => Schema::getColumnListing('payrollregister_unposted_s'),
        };
    }
}
