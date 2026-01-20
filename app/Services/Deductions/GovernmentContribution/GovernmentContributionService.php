<?php

namespace App\Services\Deductions\GovernmentContribution;

class GovernmentContributionService
{
    //

    public function __construct(
        protected HDMFContributionService $hdmf_service,
        protected PhilHealthContributionService $phic_service,
        protected SSSContributionService $sss_service,
        protected WithHoldingTaxService $wtax_service
    )
    {
       
    }

    public function computeHDMF($period,$employee)
    {
        return $this->hdmf_service->compute($period,$employee);
    }

    public function computeSSS($period,$employee)
    {
        return $this->sss_service->compute($period,$employee);
    }

    public function computePhilHealth($period,$employee)
    {
        return $this->phic_service->compute($period,$employee);
    }  

    public function computeWTax($period,$employee)
    {
        return $this->wtax_service->compute($period,$employee);
    }  

}

/*

deduct_phic
phic_no

sss_no
deduct_sss

hdmf_no
deduct_hdmf
hdmf_contri

tin_no

manual_wtax
deduct_wtax 

*/