<?php
namespace App\Http\Controllers\ApplyMe;

use App\Entity\ApplyMe\ApplymePayments;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use Illuminate\Http\Request;
use ScholarshipOwl\Domain\Log\PaymentMessage;

class PaymentController extends Controller
{
    use JsonResponses;

    public function create(Request $request)
    {
        $this->validate($request, [
            'sum'       => 'required|numeric',
            'response'  => 'required|string',
            'status'    => 'required|string'
        ]);

        $payment = new ApplymePayments(
            \Auth::user(),
            $request->input('sum'),
            $request->input('response'),
            $request->input('status') ?? PaymentMessage::MESSAGE_STATUS_VERIFIED
        );

        \EntityManager::persist($payment);
        \EntityManager::flush();
        return $this->jsonSuccessResponse();
    }
}