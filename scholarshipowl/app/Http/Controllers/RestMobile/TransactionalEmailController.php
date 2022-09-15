<?php

namespace App\Http\Controllers\RestMobile;

use App\Entity\Account;
use App\Facades\EntityManager;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use App\Services\Account\ForgotPasswordService;
use App\Services\PubSub\AccountService;
use App\Services\PubSub\TransactionalEmailService;
use Illuminate\Http\JsonResponse;


class TransactionalEmailController extends Controller
{
    use JsonResponses;

    /**
     * @var TransactionalEmailService
     */
    protected $tes;

    /**
     * TransactionalEmailController constructor.
     * @param TransactionalEmailService $tes
     */
    public function __construct(TransactionalEmailService $tes)
    {
        $this->tes = $tes;
    }

    /**
     * Premium membership invitation
     *
     * @throws \Exception
     */
    public function triggerAppMembershipInvite()
    {
        /** @var Account $account */
        $account = \Auth::user();
        /** @var AccountService $as */
        $as = app(AccountService::class);
        $token = $as->setRegenerateLoginToken(true)
            ->populateMergeFields(
                [$account],
                [AccountService::FIELD_LOGIN_TOKEN]
            )[$account->getAccountId()]['login_token'];
        $data = [
            'login_token' => $token,
            'url' => "https://scholarshipowl.com/ml/{$token}?web-redirect=/secure-upgrade"
        ];
        $this->tes->sendCommonEmail($account, TransactionalEmailService::APP_MEMBERSHIP_INVITE, $data);

        return $this->jsonSuccessResponse([]);
    }

    /**
     * Magic link
     *
     * @throws \Exception
     */
    public function triggerAppMagicLink($email, AccountService $as)
    {
        /** @var Account $account */
        $account = \EntityManager::getRepository(Account::class)->findOneBy(['email' => $email]);

        if (!$account) {
            return $this->jsonErrorResponse('Account with specified email is not found', JsonResponse::HTTP_BAD_REQUEST);
        }

        $token = $as->setRegenerateLoginToken(true)
            ->populateMergeFields(
                [$account],
                [AccountService::FIELD_LOGIN_TOKEN]
            )[$account->getAccountId()]['login_token'];

        $data = [
            'login_token' => $token,
            'url' => "https://scholarshipowl.com/ml/{$token}"
        ];

        $this->tes->sendCommonEmail($account, TransactionalEmailService::APP_MAGIC_LINK, $data);

        return $this->jsonSuccessResponse([]);
    }

    /**
     * Email with password reset link
     *
     * @throws \Exception
     */
    public function resetPassword($email, ForgotPasswordService $forgotPasswordService)
    {
        /** @var Account $account */
        $account = \EntityManager::getRepository(Account::class)->findOneBy(['email' => $email]);

        if (!$account) {
            return $this->jsonErrorResponse('Account with specified email is not found', JsonResponse::HTTP_BAD_REQUEST);
        }

        $token = $forgotPasswordService->updateToken($account)->getToken();

        $appRedirect = urlencode("/handle-password-reset/?token={$token}");
        $webRedirect = urlencode(route('reset-password', ['token' => $token]));

        $data = [
            'login_token' => $token,
            'url' => "https://scholarshipowl.com/resolver/?app-redirect={$appRedirect}&web-redirect={$webRedirect}"
        ];

        $this->tes->sendCommonEmail($account, TransactionalEmailService::APP_PASSWORD_RESET, $data);

        return $this->jsonSuccessResponse([]);
    }
}
