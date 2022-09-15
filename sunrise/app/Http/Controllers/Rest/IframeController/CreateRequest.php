<?php

/**
 * Auto-generated class file
 */

declare(strict_types=1);

namespace App\Http\Controllers\Rest\IframeController;

use App\Entities\ScholarshipTemplate;
use App\Rules\Data;

class CreateRequest extends UpdateRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return parent::entityRules() + [
            'data.relationships.template' => ['required', new Data(ScholarshipTemplate::class)],
        ];
    }
}
