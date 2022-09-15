<?php
namespace App\Http\Controllers\Admin\Features;

use App\Entity\FeatureBannerSet;
use App\Entity\FeatureContentSet;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;
use Illuminate\Http\Request;

class IndexController extends BaseFeatureSetController
{
    protected $entity = FeatureSet::class;
    protected $indexRoute = 'admin::features.index';

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $showDeleted = $request->get('showDeleted');
        $criteria = ['deleted' => false];
        $this->addBreadcrumb('Features', 'features.index');

        if ($showDeleted) {
            $criteria = [];
        }

        return $this->view('Features', 'admin.features.index', [
            'sets' => $this->repository->findBy($criteria),
            'showDeleted' => $showDeleted,
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
        /** @var FeatureSet $set */
        $set = $id !== null ? $this->repository->findById($id) : null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $rules = [
                'name'                => 'required',
                'desktop_payment_set' => 'required|exists:' . FeaturePaymentSet::class . ',id',
                'mobile_payment_set'  => 'required|exists:' . FeaturePaymentSet::class . ',id',
                'content_set'         => 'required|exists:' . FeatureContentSet::class . ',id',
            ];

            if (!$set || $set->getName() != $request->get('name')) {
                $rules['name'] .=  '|unique:'.$this->entity;
            }

            $this->validate($request, $rules);

            if ($set) {
                $set->setName($request->get('name'));
                $set->setDesktopPaymentSet($request->get('desktop_payment_set'));
                $set->setMobilePaymentSet($request->get('mobile_payment_set'));
                $set->setContentSet($request->get('content_set'));

                $this->em->flush($set);

            } else {
                $set = new FeatureSet(
                    $request->get('name'),
                    $request->get('desktop_payment_set'),
                    $request->get('mobile_payment_set'),
                    $request->get('content_set')
                );

                $this->em->persist($set);
                $this->em->flush($set);
            }

            return \Redirect::route('admin::features.edit', $set->getId())
                ->with('message', sprintf('Set \'%s\' saved!', $set));
        }

        $this->addBreadcrumb('Features', 'features.index');
        $this->addPostBreadcrumb('features.edit', $id);

        return $this->view(($id ? 'Edit' : 'Create') . ' set', 'admin.features.edit', ['set' => $set]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        /** @var FeatureSet $set */
        $set = $this->repository->findById($id);
        $set->setDeleted(true);
        $this->em->flush($set);

        return \Redirect::route('admin::features.index')
            ->with('message', sprintf('Feature set \'%s\' removed!', $set));
    }
}
