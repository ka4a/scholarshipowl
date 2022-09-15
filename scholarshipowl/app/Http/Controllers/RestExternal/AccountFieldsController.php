<?php

namespace App\Http\Controllers\RestExternal;

use App\Entity\Account;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use App\Services\PubSub\AccountService;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;


class AccountFieldsController extends Controller
{
    use JsonResponses;

    public function __construct(Request $request)
    {
        $passedSecret = $request->header('x-api-key');
        $secret = config('services.mautic.api_key');
        $whiteIps = explode(
            ',', str_replace(' ', '', config('services.mautic.white_ips'))
        );

        if (!in_array($request->ip(), $whiteIps) && !in_array('*', $whiteIps)) {
            abort(401, 'Forbidden IP address');
        }

        if (!$passedSecret || strcmp($passedSecret, $secret) !== 0) {
            abort(401, 'Invalid API key');
        }
    }

    /**
     * Return account field values. If login_token requested, a new token is generated.
     *
     * @param AccountService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAndRefreshAccountFields(AccountService $service, Request $request)
    {
        $accountIds = $request->get('accounts', []);

        if (count($accountIds) > 1000) {
            abort(400, 'Maximum number of accounts per request - 1000' );
        }

        $fields = $request->get('fields', []);
        $accounts = \EntityManager::getRepository(Account::class)->findBy(['accountId' => $accountIds]);
        $service->setRegenerateLoginToken(true);
        $fields = $service->populateMergeFields($accounts, $fields);

        return $this->jsonSuccessResponse($fields);
    }

    /**
     * List all available account fields
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
    public function listAccountFields()
    {
        return $this->jsonSuccessResponse(array_values(AccountService::fields()));
    }
}
