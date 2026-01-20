<?php

namespace App\Repositories\Employee;
use Illuminate\Support\Facades\DB;

class NonConfiRepository
{

    /**
     * Get employee by biometric id
     * @param  mixed $biometric_id
     * @return object
     */

    public function find($biometric_id) {
        return  DB::table('employees')
        ->where('biometric_id',$biometric_id)
        ->first();
    }

    /**
    * Get all employees with active status
    * @return collection
    */

    public function getAllActive() {
        return DB::table('employees')->where('emp_level',5)->where('exit_status',1);
        //->where('biometric_id',847);
    }

    /**
     * Get all employees with DTR and with in the collection            
     * @param  mixed $period
     * @return collection
     */

    public function getAllWithDTR($period){
     
        return DB::table('edtr')
            ->whereBetween('dtr_date',[$period->date_from,$period->date_to])
            ->whereIn('edtr.biometric_id',$this->getAllActive()->pluck('biometric_id'))
            ->leftJoin('employees','edtr.biometric_id','employees.biometric_id')
            ->select('employees.biometric_id','employees.lastname','employees.firstname','employees.pay_type')
            ->distinct()
            ->orderBy('employees.lastname','asc')
            ->orderBy('employees.firstname','asc')
            ->get();
    }
}
