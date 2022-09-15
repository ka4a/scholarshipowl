<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\JsonResponses;
use App\Mail\Contact;
use App\Mail\ListYourScholarship;
use Illuminate\Support\Facades\Mail;
use ScholarshipOwl\Data\Service\Payment\PopupService;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Validation\Validator;

class WebsiteController extends BaseController
{
    use JsonResponses;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSendToPartnerAction(Request $request)
    {
        /** @var Validator $validator */
        $validator = $this->getValidationFactory()
            ->make($request->all(), [
                'name'    => 'required',
                'email'   => 'required|email',
                'content' => 'required',
            ]);

        if ($validator->fails()) {
            return $this->jsonErrorResponse($validator->errors()->getMessages());
        }

        $data = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'content' => $request->get('content')
        ];

        Mail::send(new ListYourScholarship($data));

        return $this->jsonSuccessResponse();
    }

    /**
     * @param int $popupId
     *
     * @return mixed
     */
    public function popupAction($popupId)
    {
        $service = new PopupService();

        if ($popup = $service->getPopup($popupId)) {
            return $this->getOkModel('popup')
                ->setData($popup->toArray())
                ->send();
        }

        return abort(404);
	}
}
