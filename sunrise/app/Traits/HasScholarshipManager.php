<?php namespace App\Traits;

use App\Services\ScholarshipManager;

trait HasScholarshipManager
{
    /**
     * @return ScholarshipManager
     */
    public function sm()
    {
        return app(ScholarshipManager::class);
    }
}
