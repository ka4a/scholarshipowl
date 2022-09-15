<?php namespace App\Rest\Traits;

use App\Entity\Account;
use App\Entity\Profile;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait RestAuthorization
{

    /**
     * @param Request $request
     * @param string  $param
     *
     * @return Account
     * @throws ValidationException
     * @throws \InvalidArgumentException
     */
    protected function validateAccount(Request $request, string $param = 'accountId') : Account
    {
        if ($request->has($param)) {
            $this->validate($request, [$param => 'entity:Account']);
            return \EntityManager::findById(Account::class, $request->get($param));
        }

        if (null === ($account = $this->getAuthenticatedAccount())) {
            throw new \InvalidArgumentException(sprintf('Param %s not provided', $param));
        }

        return $account;
    }

    /**
     * @return null|Account
     */
    protected function getAuthenticatedAccount()
    {
        if (($account = \Auth::user()) && $account instanceof Account) {
            return $account;
        }

        return null;
    }
}
