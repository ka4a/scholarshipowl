<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Http\Controllers\ApplyMe;

use App\Entity\Application;
use App\Http\Controllers\Rest\ApplicationRestController;

class ApplicationController extends ApplicationRestController
{
    /**
     * @param  $account
     * @param  $scholarship
     * @return Application
     */
    protected function apply($account, $scholarship)
    {
        return $this->applicationService->applyScholarship($account, $scholarship, true);
    }
}
