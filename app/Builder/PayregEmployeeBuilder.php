<?php

namespace App\Builder;
use App\Builder\IPayregEmployee;
use App\Decorator\Payroll\Hours;
use App\Decorator\Payroll\LegalHolidayDecorator;
use App\Decorator\Payroll\NighDifferentialDecorator;
use App\Decorator\Payroll\OvertimeDecorator;
use App\Decorator\Payroll\RestDayDecorator;
use App\Decorator\Payroll\SpecialHolidayDecorator;
use App\Decorator\Payroll\SpecialHolidayRestDayDecorator;

class PayregEmployeeBuilder implements IPayregEmployee
{
    //
    protected $employeeObject;
    protected $schema;

    protected $fields;

    protected $dailyRateStrategy;  
    protected $rates;

    protected $dtr;

    /*
    * Set employee
    * @param mixed $employeeObject
    */

    public function setEmployee($employeeObject){
        $this->employeeObject = $employeeObject;
        return $this;
    }

    public function setEmployeeDailyTimeRecord($dtr)
    {
        $this->dtr = $dtr;
        return $this;
    }

    /*
    * Set schema and fields
    * @param mixed $schema database schema
    */

    public  function setSchema($schema){
        $this->schema = $schema;

        foreach($this->schema as $dataBaseColumn){
            $this->fields[$dataBaseColumn] = null;
        }
        unset($this->fields['line_id']);

        return $this;
    }

    public function setDailyRateStrategy($dailyRateStrategy){
        $this->dailyRateStrategy = $dailyRateStrategy;
        return $this;
    }

    public function setRates(){

        $this->rates = array(
            'daily_rate' => round($this->dailyRateStrategy->compute($this->employeeObject->getBasicSalary()),3),
            'hourly_rate' => round(($this->dailyRateStrategy->compute($this->employeeObject->getBasicSalary()))/8,3),
            'minutely_rate' => round(($this->dailyRateStrategy->compute($this->employeeObject->getBasicSalary()))/8/60,3),
        );

        // dd( $this->rates['daily_rate']);

        $this->fields['daily_rate'] = $this->rates['daily_rate'];
        $this->fields['basic_salary'] = round($this->employeeObject->getBasicSalary(),2);
        
        return $this;
    }

    public function computeLateAmount(){
        $this->fields['late'] = $this->dtr->late;
        $this->fields['late_eq'] = round($this->dtr->late/60,2);
        $this->fields['late_eq_amount'] = round($this->dtr->late * $this->rates['minutely_rate'],2);

        return $this;
    }

    public function computeUnderTimeAmount(){
        $this->fields['under_time'] = (float) $this->dtr->under_time;
        $this->fields['under_time_amount'] = round($this->dtr->under_time * $this->rates['minutely_rate'],2);

        return $this;
    }

    public function computeVacationLeaveAmount()
    {
        $this->fields['vl_wpay'] = (float) $this->dtr->vl_wp;
        $this->fields['vl_wpay_amount'] = round($this->dtr->vl_wp * $this->rates['daily_rate'],2);
        
        $this->fields['vl_wopay'] = (float) $this->dtr->vl_wop;
        $this->fields['vl_wopay_amount'] = round($this->dtr->vl_wop * $this->rates['daily_rate'],2);
        
        return $this;
    }

    public function computeSickLeaveAmount(){
        $this->fields['sl_wpay'] = (float) $this->dtr->sl_wp;
        $this->fields['sl_wpay_amount'] = round($this->dtr->sl_wp * $this->rates['daily_rate'],2);
        
        $this->fields['sl_wopay'] = (float) $this->dtr->sl_wop;
        $this->fields['sl_wopay_amount'] = round($this->dtr->sl_wop * $this->rates['daily_rate'],2);
        
        return $this;
    }


    public function computeSVLAmount()
    {
        return $this;
    }

    public function computeBirthdayLeaveAmount()
    {
        $this->fields['bl_wpay'] = (float) $this->dtr->bl;
        $this->fields['bl_wpay_amount'] = round($this->dtr->bl * $this->rates['daily_rate'],2);
        
        return $this;
    }

    public function computeBereavementLeaveAmount()
    {
        $this->fields['brv'] = (float) $this->dtr->brv;
        $this->fields['brv_amount'] = round($this->dtr->brv * $this->rates['daily_rate'],2);
        
        return $this;
    }

    public function computeAbsenceAmount()
    {
        $absences = (float) $this->dtr->awol + $this->fields['vl_wopay'] + $this->fields['sl_wopay'];

        $this->fields['absences'] = $absences;
        $this->fields['absences_amount'] = round($absences * $this->rates['daily_rate'],2);

        return $this;
    }

    public function computeReglarOverTimeAmount()
    {
        
        $this->fields['reg_ot'] = (float) $this->dtr->over_time;
        
        $hours = new Hours($this->dtr->over_time,$this->rates['hourly_rate'],'OVERTIME',$this->employeeObject->getPayType(),'REGULARDAY');
        $overtime = new OvertimeDecorator($hours);
        
        $this->fields['reg_ot_amount'] = round($overtime->compute(),2);

        return $this;
    }

    public function computeRegularNightDifferentialAmount()
    {
        $this->fields['reg_nd'] = (float) $this->dtr->night_diff;
        $nd = new NighDifferentialDecorator(new Hours($this->dtr->night_diff,$this->rates['hourly_rate'],'NIGHTSHIFT',$this->employeeObject->getPayType(),'REGULARDAY'));
        $this->fields['reg_nd_amount'] = round($nd->compute(),2);

        $this->fields['reg_ndot'] = (float) $this->dtr->night_diff_ot;
        $nd_ot = new OvertimeDecorator(new NighDifferentialDecorator (new Hours($this->dtr->night_diff_ot,$this->rates['hourly_rate'],'OVERTIME',$this->employeeObject->getPayType(),'REGULARDAY')));
        $this->fields['reg_ndot_amount'] = round($nd_ot->compute(),2);

        return $this;
    }

    public function computeLegalHolidayHrsAndPremiumAmount()
    {

        $this->fields['leghol_count'] = (float) $this->dtr->reghol_pay;
        $this->fields['leghol_count_amount'] = (float) $this->dtr->reghol_pay * $this->rates['daily_rate'];

        $this->fields['leghol_hrs'] = (float) $this->dtr->reghol_hrs;
        $leghol = new LegalHolidayDecorator(new Hours($this->dtr->reghol_hrs,$this->rates['hourly_rate'],'REGULARTIME',$this->employeeObject->getPayType(),'HOLIDAY'));
        $this->fields['leghol_hrs_amount'] = round($leghol->compute(),2);

        $this->fields['leghol_ot'] = (float) $this->dtr->reghol_ot;
        $leghol_ot = new OvertimeDecorator(new LegalHolidayDecorator(new Hours($this->dtr->reghol_ot,$this->rates['hourly_rate'],'OVERTIME',$this->employeeObject->getPayType(),'HOLIDAY')));
        $this->fields['leghol_ot_amount'] = round($leghol_ot->compute(),2);

        $this->fields['leghol_nd'] = (float) $this->dtr->reghol_nd;
        $leghol_nd =  new NighDifferentialDecorator(new OvertimeDecorator(new LegalHolidayDecorator(new Hours($this->dtr->reghol_nd,$this->rates['hourly_rate'],'NIGHTSHIFT',$this->employeeObject->getPayType(),'HOLIDAY'))));
        $this->fields['leghol_nd_amount'] = round( $leghol_nd->compute(),2);

        $this->fields['leghol_rd'] = (float) $this->dtr->reghol_rd;
        $leghol_rd =  new RestDayDecorator(new LegalHolidayDecorator(new Hours($this->dtr->reghol_nd,$this->rates['hourly_rate'],'REGULARTIME',$this->employeeObject->getPayType(),'HOLIDAY')));
        $this->fields['leghol_rd_amount'] = round($leghol_rd->compute(),2);

        $this->fields['leghol_rdot'] = (float) $this->dtr->reghol_rdot;
        $leghol_rdot = new OvertimeDecorator(new RestDayDecorator(new LegalHolidayDecorator(new Hours($this->dtr->reghol_rdot,$this->rates['hourly_rate'],'OVERTIME',$this->employeeObject->getPayType(),'RESTDAY'))));
        $this->fields['leghol_rdot_amount'] = round($leghol_rdot->compute(),2);

        $this->fields['leghol_ndot'] = (float) $this->dtr->reghol_ndot;
        $reghol_ndot =  new OvertimeDecorator(new NighDifferentialDecorator(new LegalHolidayDecorator(new Hours($this->dtr->reghol_ndot,$this->rates['hourly_rate'],'OVERTIME',$this->employeeObject->getPayType(),'HOLIDAY'))));
        $this->fields['leghol_ndot_amount'] = round($reghol_ndot->compute(),2);

        $this->fields['leghol_rdnd'] = (float) $this->dtr->reghol_rdnd;
        $reghol_rdnd = new LegalHolidayDecorator(new NighDifferentialDecorator(new RestDayDecorator(new Hours($this->dtr->reghol_ndot,$this->rates['hourly_rate'],'NIGHTSHIFT',$this->employeeObject->getPayType(),'RESTDAY'))));
        $this->fields['leghol_rdnd_amount'] =  round($reghol_rdnd->compute(),2);

        $this->fields['leghol_rdndot'] = (float) $this->dtr->reghol_rdndot;
        $leghol_rdndot = new OvertimeDecorator(new LegalHolidayDecorator(new NighDifferentialDecorator(new RestDayDecorator(new Hours($this->dtr->reghol_rdndot,$this->rates['hourly_rate'],'OVERTIME',$this->employeeObject->getPayType(),'RESTDAY')))));
        $this->fields['leghol_rdndot_amount'] =  round($leghol_rdndot->compute(),2);

//           "leghol_rdndot" => null
//   "leghol_rdndot_amount" => null

        return $this;
    }

    public function computeSpecialHolidayAndPremumAmount()
{   
        $this->fields['sphol_count'] = (float) $this->dtr->sphol_pay;
        $this->fields['sphol_count_amount'] = (float) $this->dtr->sphol_pay * $this->rates['daily_rate'];

        $this->fields['sphol_hrs'] = (float) $this->dtr->sphol_hrs;
        $sphol = new SpecialHolidayDecorator(new Hours($this->dtr->sphol_hrs,$this->rates['hourly_rate'],'REGULARTIME',$this->employeeObject->getPayType(),'HOLIDAY'));
        $this->fields['sphol_hrs_amount'] = round($sphol->compute(),2);

        $this->fields['sphol_ot'] = (float) $this->dtr->sphol_ot;
        $sphol_ot = new OvertimeDecorator(new SpecialHolidayDecorator(new Hours($this->dtr->sphol_ot,$this->rates['hourly_rate'],'OVERTIME',$this->employeeObject->getPayType(),'HOLIDAY')));
        $this->fields['sphol_ot_amount'] = round($sphol_ot->compute(),2);

        $this->fields['sphol_rd'] = (float) $this->dtr->sphol_rd;
        $sphol_rd = new SpecialHolidayRestDayDecorator(new Hours($this->dtr->sphol_rd,$this->rates['hourly_rate'],'REGULARTIME',$this->employeeObject->getPayType(),'HOLIDAY'));
        $this->fields['sphol_rd_amount'] = round($sphol_rd->compute(),2);
        /*
 
        +"": "0.00"
        +"": "0.00"
        +"": "0.00"
        +"sphol_rdnd": "0.00"
        +"sphol_rdot": "0.00"
        +"sphol_nd": "0.00"
        +"sphol_ndot": "0.00"

        
        

        sphol_nd
        sphol_nd_amount

        
        

        sphol_ndot
        sphol_ndot_amount

        sphol_rd
        sphol_rd_amount
*/

        return $this;
    }

    public function computeBasicPay()
    {

    }

    public function computeRestDayAmount()
    {
        $this->fields['rd_hrs'] = (float) $this->dtr->restday_hrs;
        $rd = new RestDayDecorator(new Hours($this->dtr->restday_hrs,$this->rates['hourly_rate'],'REGULARTIME',$this->employeeObject->getPayType(),'RESTDAY'));
        $this->fields['rd_hrs_amount'] = round($rd->compute(),2);
       
        $this->fields['rd_ot'] = (float) $this->dtr->restday_ot;
        $rd_ot = new OvertimeDecorator(new RestDayDecorator(new Hours($this->dtr->restday_ot,$this->rates['hourly_rate'],'OVERTIME',$this->employeeObject->getPayType(),'RESTDAY')));
        $this->fields['rd_ot_amount'] = round($rd_ot->compute(),2);

        $this->fields['rd_nd'] = (float) $this->dtr->restday_nd;
        $rd_nd = new NighDifferentialDecorator(new RestDayDecorator(new Hours($this->dtr->restday_nd,$this->rates['hourly_rate'],'NIGHTSHIFT',$this->employeeObject->getPayType(),'RESTDAY')));
        $this->fields['rd_nd_amount'] = round($rd_nd->compute(),2);

        $this->fields['rd_ndot'] = (float) $this->dtr->restday_ndot;
        $rd_ndot = new OvertimeDecorator(new NighDifferentialDecorator(new RestDayDecorator(new Hours($this->dtr->restday_ndot,$this->rates['hourly_rate'],'OVERTIME',$this->employeeObject->getPayType(),'RESTDAY'))));
        $this->fields['rd_ndot_amount'] = round($rd_ndot->compute(),2);

        return $this;
    }

    public function getFields()
    {
        dd($this->fields,$this->rates);
    }




}

/*
  #-edtr_totals
  +"biometric_id": 158
  +"period_id": 74
  +"late": 0    ****
  +"late_eq": "0.00"    ****
  +"under_time": "0.00"    ****
  +"over_time": "0.00"
  +"night_diff": "0.00"
  +"night_diff_ot": "0.00"
  +"schedule_id": 0
  +"ndays": "0.00"

  +"ot_in": "00:00"
  +"ot_out": "00:00"
  +"restday_hrs": "0.00"
  +"restday_ot": "0.00"
  +"restday_nd": "0.00"
  +"restday_ndot": "0.00"
  +"reghol_pay": "0.00"
  +"reghol_hrs": "0.00"
  +"reghol_ot": "0.00"
  +"reghol_rd": "0.00"
  +"reghol_rdnd": "0.00"
  +"reghol_rdot": "0.00"
  +"": "0.00"
  +"reghol_ndot": "0.00"
  +"sphol_pay": "0.00"
  +"sphol_hrs": "0.00"
  +"sphol_ot": "0.00"
  +"sphol_rd": "0.00"
  +"sphol_rdnd": "0.00"
  +"sphol_rdot": "0.00"
  +"sphol_nd": "0.00"
  +"sphol_ndot": "0.00"
  +"dblhol_pay": "0.00"
  +"dblhol_hrs": "0.00"
  +"dblhol_ot": "0.00"
  +"dblhol_rd": "0.00"
  +"dblhol_rdnd": "0.00"
  +"dblhol_rdot": "0.00"
  +"dblhol_nd": "0.00"
  +"dblhol_ndot": "0.00"
  +"reghol_rdndot": "0.00"
  +"sphol_rdndot": "0.00"
  +"dblhol_rdndot": "0.00"
  +"dblsphol_pay": "0.00"
  +"dblsphol_hrs": "0.00"
  +"dblsphol_ot": "0.00"
  +"dblsphol_nd": "0.00"
  +"dblsphol_rd": "0.00"
  +"dblsphol_rdot": "0.00"
  +"dblsphol_ndot": "0.00"
  +"dblsphol_rdnd": "0.00"
  +"dblsphol_rdndot": "0.00"
  +"awol": "0.00"


  */

  /*
  #-payreg table

  "biometric_id" => null
  "period_id" => null
  "basic_salary" => null
  "is_daily" => null
  "daily_rate" => null
  "basic_pay" => null
  "late" => 0
  "late_eq" => 0.0
  "late_eq_amount" => 0.0
  "ndays" => null
  "pay_type" => null
  "under_time" => null
  "sss_prem" => null
  "phil_prem" => null
  "hdmf_contri" => null
  "daily_allowance" => null
  "semi_monthly_allowance" => null
  "under_time_amount" => null

  "absences" => null
  "absences_amount" => null
  "reg_ot" => null
  "reg_ot_amount" => null
  "reg_nd" => null
  "reg_nd_amount" => null
  "reg_ndot" => null
  "reg_ndot_amount" => null
  "rd_hrs" => null
  "rd_hrs_amount" => null
  "rd_ot" => null
  "rd_ot_amount" => null
  "rd_nd" => null
  "rd_nd_amount" => null
  "rd_ndot" => null
  "rd_ndot_amount" => null
  "leghol_count" => null
  "leghol_count_amount" => null
  "leghol_hrs" => null
  "leghol_hrs_amount" => null
  "leghol_ot" => null
  "leghol_ot_amount" => null
  "leghol_nd" => null
  "leghol_nd_amount" => null
  "leghol_rd" => null
  "leghol_rd_amount" => null
  "leghol_rdot" => null
  "leghol_rdot_amount" => null
  "leghol_ndot" => null
  "leghol_ndot_amount" => null
  "leghol_rdnd" => null
  "leghol_rdnd_amount" => null
  "leghol_rdndot" => null
  "leghol_rdndot_amount" => null
  "sphol_count" => null
  "sphol_count_amount" => null
  "sphol_hrs" => null
  "sphol_hrs_amount" => null
  "sphol_ot" => null
  "sphol_ot_amount" => null
  "sphol_nd" => null
  "sphol_nd_amount" => null
  "sphol_rd" => null
  "sphol_rd_amount" => null
  "sphol_rdot" => null
  "sphol_rdot_amount" => null
  "sphol_ndot" => null
  "sphol_ndot_amount" => null
  "sphol_rdnd" => null
  "sphol_rdnd_amount" => null
  "sphol_rdndot" => null
  "sphol_rdndot_amount" => null
  "dblhol_count" => null
  "dblhol_count_amount" => null
  "dblhol_hrs" => null
  "dblhol_hrs_amount" => null
  "dblhol_ot" => null
  "dblhol_ot_amount" => null
  "dblhol_nd" => null
  "dblhol_nd_amount" => null
  "dblhol_rd" => null
  "dblhol_rd_amount" => null
  "dblhol_rdot" => null
  "dblhol_rdot_amount" => null
  "dblhol_ndot" => null
  "dblhol_ndot_amount" => null
  "dblhol_rdnd" => null
  "dblhol_rdnd_amount" => null
  "dblhol_rdndot" => null
  "dblhol_rdndot_amount" => null
  "gross_pay" => null
  "gross_total" => null
  "total_deduction" => null
  "net_pay" => null
  "sss_wisp" => null
  "user_id" => null
  "generated_on" => null
  "emp_level" => null
  "actual_dblhol" => null
  "actual_sphol" => null
  "actual_reghol" => null
  "svl" => null
  "svl_amount" => null
  "wtax" => null
  "actual_dblsphol" => null
  "dblsphol_count" => null
  "dblsphol_count_amount" => null
  "dblsphol_hrs" => null
  "dblsphol_hrs_amount" => null
  "dblsphol_ot" => null
  "dblsphol_ot_amount" => null
  "dblsphol_nd" => null
  "dblsphol_nd_amount" => null
  "dblsphol_rd" => null
  "dblsphol_rd_amount" => null
  "dblsphol_rdot" => null
  "dblsphol_rdot_amount" => null
  "dblsphol_ndot" => null
  "dblsphol_ndot_amount" => null
  "dblsphol_rdnd" => null
  "dblsphol_rdnd_amount" => null
  "dblsphol_rdndot" => null
  "dblsphol_rdndot_amount" => null

  */