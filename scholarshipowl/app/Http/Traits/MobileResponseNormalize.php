<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait MobileResponseNormalize
{
    /**
     * Skip scholarships with survey and special elb. requirements
     *
     * @param JsonResponse $response
     */
    protected function filterAndNormalize(JsonResponse $response)
    {
        $payload = $response->getData();

        $skip = function($payload) {
            $unsetTotal = 0;
            foreach ($payload->data ?? [] as $k => $item) {
                if ((isset($item->requirements->specialEligibility) && count($item->requirements->specialEligibility)) ||
                    (isset($item->scholarship->requirements->specialEligibility) &&
                        count($item->scholarship->requirements->specialEligibility))) {
                    unset($payload->data[$k]);
                    $unsetTotal++;
                    continue;
                }

                if ((isset($item->requirements->survey) && count($item->requirements->survey)) ||
                    (isset($item->scholarship->requirements->survey) && count($item->scholarship->requirements->survey))) {
                    unset($payload->data[$k]);
                    $unsetTotal++;
                    continue;
                }
            }

            $meta = $payload->meta ?? null;

            if ($unsetTotal > 0) {
                if ($meta && isset($meta->count)) {
                    $meta->count -= $unsetTotal;
                }

                $payload->data = array_values($payload->data);
            }
        };

        $skip($payload);

        $response->setData($payload);
    }
}
