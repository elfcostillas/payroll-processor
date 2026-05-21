<?php

namespace App\Services\DTR;

use App\Repositories\DTR\HolidayRepository;

class HolidayService
{
    //
    public function __construct(protected HolidayRepository $holidayRepository)
    {

    }

    public function getHolidayCounts($biometric_id,$period_id)
    {
        return $this->holidayRepository->getHolidayCounts($biometric_id,$period_id);
    }   
}
