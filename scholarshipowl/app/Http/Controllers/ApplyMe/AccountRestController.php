<?php namespace App\Http\Controllers\ApplyMe;

use App\Entity\Account;
use App\Entity\Resource\ApplyMe\AccountResource;
use App\Http\Controllers\Rest\AccountRestController as OriginalAccountRestController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;

class AccountRestController extends OriginalAccountRestController
{
    /**
     * @return AccountResource
     */
    public function getResource()
    {
        return new AccountResource();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email'     => 'email|required|exists:App\Entity\Account,email,domain,' . \Domain::get()->getId(),
        ]);

        /** @var Account $account */
        $account = $this->getRepository()->findByEmail($request->input('email'));

        $newPassword = $this->accountService->generateNewPassword($account);

        \EntityManager::flush();

        \Mail::send('emails.user.apply_me_forgot_password', [
            'user' => $account, 'password' => $newPassword
        ], function (Message $m) use ($account) {
            $m->from('info@apply.me', 'Apply.me Team');
            $m->to($account->getEmail(), $account->getProfile()->getFirstName())->subject('Password reset');
        });

        return $this->jsonSuccessResponse();
    }
}
