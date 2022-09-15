<?php

namespace App\Http\Controllers\Rest;

use App\Entity\FeatureSet;
use App\Entity\Resource\FeatureSet\FeatureContentSetResource;
use App\Entity\Resource\FeatureSet\FeaturePaymentSetResource;
use App\Entity\Resource\FeatureSetResource;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use Illuminate\Http\Request;

class FeatureSetRestController extends Controller
{
    use JsonResponses;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {

        $data = [];
        /** @var FeatureSet $fset */
        $fset = FeatureSet::config();

        if ($fields = request()->get('fields')) {
            $fields = str_word_count($fields, 1);
        }

        if (!$fields) {
            $data = FeatureSetResource::entityToArray($fset);
        } else {
            $data['id'] = $fset->getId();
            $data['name'] = $fset->getName();

            if (in_array('desktopPaymentSet', $fields)) {
                $data['desktopPaymentSet'] = FeaturePaymentSetResource::entityToArray($fset->getDesktopPaymentSet());
            }

            if (in_array('mobilePaymentSet', $fields)) {
                $data['mobilePaymentSet'] = FeaturePaymentSetResource::entityToArray($fset->getMobilePaymentSet());
            }

            if (in_array('contentSet', $fields)) {
                $data['contentSet'] = FeatureContentSetResource::entityToArray($fset->getContentSet());
            }
        }

        return $this->jsonSuccessResponse($data);
    }
}
