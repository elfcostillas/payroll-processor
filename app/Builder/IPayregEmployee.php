<?php

namespace App\Builder;

interface IPayregEmployee
{
    //
    public function computeLateAmount();

    public function computeUnderTimeAmount();

    public function computeAbsenceAmount();

    public function computeBasicPay($basicPayStrategy);

    public function computeVacationLeaveAmount();

    public function computeSickLeaveAmount();

    public function computeSVLAmount();

    public function computeBirthdayLeaveAmount();

    public function computeBereavementLeaveAmount();

    public function computeReglarOverTimeAmount();

    public function computeRegularNightDifferentialAmount();

    public function computeLegalHolidayHrsAndPremiumAmount();

    public function getFields();






    // public function computeOverTime();

}
