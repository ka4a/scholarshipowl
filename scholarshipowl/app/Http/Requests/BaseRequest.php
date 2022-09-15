<?php namespace App\Http\Requests;

use App\Entity\Account;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * @return Gate
     */
    public function gate()
    {
        return app(Gate::class);
    }
}
