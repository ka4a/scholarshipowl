<?php namespace App\Transformers;

use App\Entities\ScholarshipTemplateLog;
use League\Fractal\TransformerAbstract;

class ScholarshipTemplateLogTransformer extends TransformerAbstract
{
    /**
     * @param ScholarshipTemplateLog $log
     * @return array
     */
    public function transform(ScholarshipTemplateLog $log)
    {
        return [
            'id' => $log->getId(),
            'data' => $log->getData(),
            'action' => $log->getAction(),
            'version' => $log->getVersion(),
            'loggedAt' => $log->getLoggedAt()->format('c'),
        ];
    }
}
