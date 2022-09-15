<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\MarketingSystem;
use App\Http\Middleware\TrackingParamsMiddleware;
use Illuminate\Http\Request;
use ScholarshipOwl\Data\Entity\Marketing\MarketingSystemAccount;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;

class HasOffersService
{
    const HASOFFERS_IDENTIFIER = 'transaction_id';

    /**
     * @var string
     */
    protected static $cookieAffiliateId;

    /**
     * @return string
     */
    public static function getCookieAffiliateId()
    {
        if (static::$cookieAffiliateId === null) {
            static::$cookieAffiliateId = 'none';

            if ($cookie = \Request::cookie(TrackingParamsMiddleware::COOKIE_MARKETING_SYSTEM)) {
                static::$cookieAffiliateId = @unserialize($cookie)['url_params']['affiliate_id'] ?? 'none';
            }
        }

        return static::$cookieAffiliateId;
    }

    /**
     * @param $affiliateId
     */
    public static function setCookieAffiliateId($affiliateId)
    {
        static::$cookieAffiliateId = $affiliateId;
    }

    /**
     * @param Request $request
     *
     * @return array|bool
     */
    public function getTrackingParams(Request $request)
    {
        $params = false;

        if ($request->query->has(static::HASOFFERS_IDENTIFIER)) {
            $params = [];
            foreach ($request->query as $param => $value) {
                if (!preg_match('/^{.*}$/', $value)) {
                    $params[$param] = $value;
                }
            }
        }

        return $params;
    }

    /**
     * Method to save marketing parameters from cookie or query string
     *
     * @param Request       $request
     * @param Account|int   $account
     */
    public function saveMarketingSystemAccount(Request $request, $account)
    {
        $accountId = ($account instanceof Account) ? $account->getAccountId() : $account;

        try {
            // Try With Cookie First
            if ($cookie = $this->getCookie($request)) {
                $marketingSystemAccount = new MarketingSystemAccount();
                $marketingSystemAccount->setAccountId($accountId);
                $marketingSystemAccount->getMarketingSystem()->setMarketingSystemId(MarketingSystem::HAS_OFFERS);
                $marketingSystemAccount->setData(@$cookie["url_params"]);

                $marketingSystemService = new MarketingSystemService();
                $marketingSystemService->setMarketingSystemAccount($marketingSystemAccount);
            } else {
                if ($params = $this->getTrackingParams($request)) {
                    $marketingSystemAccount = new MarketingSystemAccount();
                    $marketingSystemAccount->setAccountId($accountId);
                    $marketingSystemAccount->getMarketingSystem()->setMarketingSystemId(MarketingSystem::HAS_OFFERS);
                    $marketingSystemAccount->setData($params);

                    $marketingSystemService = new MarketingSystemService();
                    $marketingSystemService->setMarketingSystemAccount($marketingSystemAccount);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
            die('error');
        }
    }

    /**
     * @param Request $request
     *
     * @return bool|array
     */
    protected function getCookie(Request $request)
    {
        if ($cookie = $request->cookies->get(TrackingParamsMiddleware::COOKIE_MARKETING_SYSTEM)) {
            return unserialize($cookie);
        }

        return false;
    }
}
