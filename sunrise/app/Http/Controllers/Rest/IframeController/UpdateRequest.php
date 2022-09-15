<?php

/**
 * Auto-generated class file
 */

declare(strict_types=1);

namespace App\Http\Controllers\Rest\IframeController;

use App\Http\Requests\RestRequest;

class UpdateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.width' => 'nullable|string|max:255',
            'data.attributes.height' => 'nullable|string|max:255',
            'data.attributes.source' => 'nullable|string|max:255',
        ];
    }
}
