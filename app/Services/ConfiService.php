<?php

namespace App\Services;
use App\Repositories\Employee\ConfiRepository;

class ConfiService
{
    protected $confiRepository;
    
    public function __construct(ConfiRepository $confiRepository) {
        $this->confiRepository = $confiRepository;
    }
}
