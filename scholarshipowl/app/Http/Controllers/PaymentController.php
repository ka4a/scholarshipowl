<?php namespace App\Http\Controllers;

use App\Entity\Account;
use App\Entity\Package;
use App\Http\Traits\JsonResponses;
use Illuminate\Http\Request;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;

abstract class PaymentController extends \App\Http\Controllers\Index\BaseController
{
    use JsonResponses;

    /**
     * @param Request $request
     * @param Package $package
     * @param Account $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function paymentPopupResponse(Request $request, Package $package, Account $account)
    {
        $service = new MarketingSystemService();
        $marketing = $service->getMarketingSystemAccount($account->getAccountId());

        if($marketing->getHasOffersOfferId() == "32" && $package->isFreeTrial()){
            \HasOffers::info(
                "HasOffers iframe: ".
                "URL: ".$request->path()."; ".
                "Account details: ".print_r(logHasoffersAccount($account), true)."; ".
                "TransactionId: ".$marketing->getHasOffersTransactionId()."; ".
                "AffiliateId: ".$marketing->getHasOffersAffiliateId()."; ".
                "GoalId: 40"
            );
        }

        return $this->jsonSuccessResponse([
            'redirect' => $this->getRedirectUrlAfterPayment($request),
            'message' => $package->getDisplaySuccessMessage(),
            'hasOffersTransactionId' => $marketing ?  $marketing->getHasOffersTransactionId() : '',
            'hasOffersAffiliateId' => $marketing ?  $marketing->getHasOffersAffiliateId() : '',
            'isFreemium' => $package->isFreemium(),
            'isFreeTrial' => $package->isFreeTrial(),
            'isMobile' => $this->isMobile(),
            'accountId' => $account->getAccountId(),
            'email' => $account->getEmail(),
            'packageId' => $package->getPackageId(),
            'packagePrice' => $package->getPrice(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    protected function getRedirectUrlAfterPayment(Request $request): string
    {
        $url = $request->session()->pull('payment_return', route('scholarships'));

        if ($url == 'apply-selected') {
            $url = '/select';
        }

        $params = @unserialize(@base64_decode($request->get('tracking_params')));

        return $url . (is_array($params) && !empty($params) ? '?' . http_build_query($params) : '');
    }
}
