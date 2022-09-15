<?php

/**
 * Auto-generated class file
 */

declare(strict_types=1);

namespace App\Http\Controllers\Rest\UserTokenController;

use App\Http\Requests\RestRequest;

class UpdateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.name' => 'string|max:255',
        ];
    }
}
