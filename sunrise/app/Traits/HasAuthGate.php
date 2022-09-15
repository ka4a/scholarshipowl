<?php namespace App\Traits;

use Illuminate\Contracts\Auth\Access\Gate;

trait HasAuthGate
{
    /**
     * @return Gate
     */
    public function gate()
    {
        return app(Gate::class);
    }
}
