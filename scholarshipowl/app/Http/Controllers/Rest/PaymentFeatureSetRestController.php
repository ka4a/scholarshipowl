<?php

namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;
use App\Entity\Resource\FeatureSet\FeatureContentSetResource;
use App\Entity\Resource\FeatureSet\FeaturePaymentSetResource;
use App\Entity\Resource\FeatureSetResource;
use App\Entity\Resource\PackageResource;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use Doctrine\ORM\EntityManager;

class PaymentFeatureSetRestController extends Controller
{
    use JsonResponses;

    const PLANS_PAGE_FSET_NAME = "PlansPage";
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {
        $data = [];
        $repo = $this->em->getRepository(FeaturePaymentSet::class);
        /**
         * @var Account $account
         */
        $account = \Auth::user();

        /**
         * @var FeaturePaymentSet $paymentFset
         */
        $paymentFset = FeaturePaymentSet::config();

        if(!is_null($paymentFset)) {
            $packagesList = $paymentFset::newPackages();
            $data['packages'] = [];

            foreach ($packagesList as $packageId => $package) {
                $data['packages'][$packageId] = PackageResource::entityToArray($package);
            }

            $data['payment_set'] = FeaturePaymentSetResource::entityToArray($paymentFset);
            $data['payment_set']['package_common_option'] = null;

            //package_common_option returns only for PlansPage feature set
            if(FeatureSet::config()->getName() == self::PLANS_PAGE_FSET_NAME) {
                $commonOptionArray = [];
                foreach ($paymentFset->getCommonOption() as $option) {
                    $option['text'] = $account->mapTags($option['text']);
                    $commonOptionArray[] = $option;
                }
                $data['payment_set']['package_common_option'] = $commonOptionArray;
            }
        }


        return $this->jsonSuccessResponse($data);
    }
}
