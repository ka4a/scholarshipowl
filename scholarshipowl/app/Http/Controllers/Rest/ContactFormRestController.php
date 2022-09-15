<?php

namespace App\Http\Controllers\Rest;

use App\Mail\Contact;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Routing\Controller;
use App\Http\Traits\JsonResponses;
use Illuminate\Support\Facades\Mail;

class ContactFormRestController extends Controller
{
    use JsonResponses;
    /**
     * @param $path
     * @param null $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postContactAction(\Illuminate\Http\Request $request, $location)
    {
        $validator = app(Factory::class)
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
            'phone' => $request->get('phone', ''),
            'content' => $request->get('content'),
            'location' => $location
        ];

        if(!$request->hasHeader('x-swagger-request')) {
            Mail::send(new Contact($data));
        }

        return $this->jsonSuccessResponse();
    }
}
