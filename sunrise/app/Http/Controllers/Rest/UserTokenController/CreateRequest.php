<?php
/**
 * Created by PhpStorm.
 * User: r3volut1oner
 * Date: 28/02/19
 * Time: 22:42
 */

namespace App\Http\Controllers\Rest\UserTokenController;


use App\Http\Requests\RestRequest;

class CreateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.name' => 'required|string|max:255',
        ];
    }
}