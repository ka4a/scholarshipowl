<?php namespace app\Transformers;

use App\Entities\SettingsLog;
use League\Fractal\TransformerAbstract;

class SettingsLogTransformer extends TransformerAbstract
{
    /**
     * @param SettingsLog $log
     * @return array
     */
    public function transform(SettingsLog $log)
    {
        return [
            'id' => $log->getId(),
            'action' => $log->getAction(),
            'version' => $log->getVersion(),
            'config' => $log->getData()['config'] ?? null,
            'loggedAt' => $log->getLoggedAt()->format('c'),
        ];
    }
}
