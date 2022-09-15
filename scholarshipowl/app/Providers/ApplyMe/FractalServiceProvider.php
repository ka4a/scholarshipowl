<?php

namespace App\Providers\ApplyMe;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\SerializerAbstract;


class FractalServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SerializerAbstract::class, DataArraySerializer::class);
    }
}
