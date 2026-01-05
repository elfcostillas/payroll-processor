<?php

namespace App\Repositories\Employee;

class ConfiRepository
{
    //
    public function find($biometric_id) {
        return DB::table('employees')->find($biometric_id);
    }

    public function getAllActive() {
        return DB::table('employees')->where('emp_level','<',5)->where('exit_status',1);
    }

    public function getAllWithDTR($period){
        dd($this->getAllActive()->pluck('biometric_id'));
        return DB::table('edtr')
        ->whereBetween('dtr_date',[$period->date_from,$period->date_to])
        ->get();
    }


}

