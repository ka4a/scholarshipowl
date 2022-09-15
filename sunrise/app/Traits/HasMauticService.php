<?php namespace App\Traits;

use App\Services\MauticService;

trait HasMauticService
{
    /**
     * @return MauticService
     */
    public function mautic()
    {
        return app(MauticService::class);
    }
}
