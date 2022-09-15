<?php namespace App\Http\Controllers\Admin\Features;

use App\Entity\FeatureAbTest;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;
use App\Entity\Package;
use App\Entity\PaymentMethod;
use App\Http\Middleware\FeatureAbTestsMiddleware;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Illuminate\Http\Request;

class PaymentSetController extends BaseFeatureSetController
{
    protected $entity = FeaturePaymentSet::class;
    protected $indexRoute = 'admin::features.payment_sets.index';

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->addBreadcrumb('Features', 'features.index');
        $this->addBreadcrumb('Payment Sets', 'features.payment_sets.index');

        return $this->view('Payment Sets', 'admin.features.payment_sets.index', [
            'paymentSets' => $this->repository->findBy([], ['id' => 'ASC']),
        ]);
    }

    /**
     * @param Request $request
     * @param null    $id
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id = null)
    {
        /** @var FeaturePaymentSet $paymentSet */
        $paymentSet = $id !== null ? $this->repository->findById($id) : null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $rules = [
                'name'           => 'required|max:255',
                'popup_title'    => 'required',
                'payment_method' => 'required|exists:' . PaymentMethod::class . ',id',
                'package.*.id'   => 'required|exists:' . Package::class . ',packageId',
                'package.*.flag' => 'numeric',
                'mobile_special_offer_only' => 'numeric'
            ];

            if (!$paymentSet || $paymentSet->getName() != $request->get('name')) {
                $rules['name'] .=  '|unique:'.$this->entity;
            }

            $this->validate($request, $rules);

            $packageList = $request->get('package');
            $packagesId = [];
            array_map(function ($package) use (&$packagesId) {
                $packagesId[] = $package['id'];
            }, $packageList);

            if (count($packageList) != count(array_unique($packagesId))) {
                return \Redirect::route('admin::features.payment_sets.edit', isset($paymentSet) ?  $paymentSet->getId() : '')
                    ->with('error', "Packages should be unique");
            }

            if ($paymentSet) {
                $paymentSet->setPaymentMethod($request->get('payment_method'));
                $paymentSet->setName($request->get('name'));
                $paymentSet->setPopupTitle($request->get('popup_title'));
                $paymentSet->setPackages($request->get('package'));
                $paymentSet->setShowNames($request->get('show_names'));
                $paymentSet->setMobileSpecialOfferOnly($request->get('mobile_special_offer_only', 0));

                $paymentSet->setCommonOption($this->getCommonOption($request));

                $this->em->flush($paymentSet);

                \Cache::tags(FeatureAbTestsMiddleware::FEATURE_SET_CACHE_TAG)->flush();
            } else {
                $paymentSet = new FeaturePaymentSet(
                    $request->get('payment_method'),
                    $request->get('name'),
                    $request->get('popup_title'),
                    $request->get('package'),
                    $request->get('show_names')
                );
                $paymentSet->setCommonOption($this->getCommonOption($request));
                $paymentSet->setMobileSpecialOfferOnly($request->get('mobile_special_offer_only', 0));

                $this->em->persist($paymentSet);
                $this->em->flush($paymentSet);
            }

            return \Redirect::route('admin::features.payment_sets.edit', $paymentSet->getId())
                ->with('message', sprintf('Payment set \'%s\' saved!', $paymentSet));
        }

        $this->addBreadcrumb('Features', 'features.index');
        $this->addBreadcrumb('Payment Sets', 'features.payment_sets.index');
        $this->addPostBreadcrumb('features.payment_sets.edit', $id);

        return $this->view(($id ? 'Edit' : 'Create') . ' payment set', 'admin.features.payment_sets.edit', [
            'set' => $paymentSet,
        ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        try {
            /** @var FeaturePaymentSet $set */
            $this->em->remove($set = $this->repository->findById($id));
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            return \Redirect::back()->withErrors(sprintf('Payment set %s used by some sets!', $id));
        }

        return \Redirect::route('admin::features.payment_sets.index')
            ->with('message', sprintf('Payment set \'%s\' removed!', $set));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getCommonOption(Request $request)
    {
        $result = [];
        $commonOptions = $request->get('package_common_option', []);
        if(!empty($commonOptions)) {
            foreach ($commonOptions as $key => $commonOption) {
                $commonOptionStatuses = $commonOption['status'];
                try {
                    $commonOptions[$key]['status'] = array_combine(array_column($request->get('package'), 'id'), $commonOptionStatuses);
                } catch (\Exception $e){
                } finally {
                    if(count($commonOptionStatuses) < 4) {
                        $element = array_pop($commonOptionStatuses);
                        array_push($commonOptionStatuses, $element, $element);
                        $commonOptions[$key]['status'] = array_combine(
                            array_column($request->get('package'), 'id'),
                            $commonOptionStatuses);
                    }
                }
            }

            $indexArray = range(1, count($commonOptions));
            $result = array_combine(array_values($indexArray), $commonOptions);
        }

        return $result;
    }
}
