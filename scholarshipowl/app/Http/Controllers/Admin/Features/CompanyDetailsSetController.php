<?php namespace App\Http\Controllers\Admin\Features;

use App\Entity\FeatureCompanyDetailsSet;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Illuminate\Http\Request;

class CompanyDetailsSetController extends BaseFeatureSetController
{
    protected $entity = FeatureCompanyDetailsSet::class;
    protected $indexRoute = 'admin::features.company_details_set.index';

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->addBreadcrumb('Features', 'features.index');
        $this->addBreadcrumb('Company Details Set', 'features.company_details_set.index');

        return $this->view('Company Details Sets', 'admin.features.company_details_set.index', [
            'companyDetailsSet' => $this->repository->findAll(),
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
        /** @var FeatureCompanyDetailsSet $companyDetails */
        $companyDetails = $id !== null ? $this->repository->findById($id) : null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate(
                    $request, [
                        'company_name' => 'required|max:255',
                        'name'         => 'required|max:255',
                        'address_1'    => 'required',
                    ],
                    [
                        'name.required' => 'The \'Fset title\' field is required.'
                    ]
            );

            if ($companyDetails) {
                $companyDetails->setCompanyName($request->get('company_name'));
                $companyDetails->setCompanyName2($request->get('company_name_2'));
                $companyDetails->setName($request->get('name'));
                $companyDetails->setAddress1($request->get('address_1'));
                $companyDetails->setAddress2($request->get('address_2'));

                $this->em->flush($companyDetails);
            } else {
                $companyDetails = new FeatureCompanyDetailsSet(
                    $request->get('company_name'),
                    $request->get('company_name_2'),
                    $request->get('name'),
                    $request->get('address_1'),
                    $request->get('address_2')
                );

                $this->em->persist($companyDetails);
                $this->em->flush($companyDetails);
            }

            return \Redirect::route('admin::features.company_details_set.edit', $companyDetails->getId())
                ->with('message', sprintf('Company details set \'%s\' saved!', $companyDetails->getName()));
        }

        $this->addBreadcrumb('Features', 'features.index');
        $this->addBreadcrumb('Company Details Sets', 'features.company_details_set.index');
        $this->addPostBreadcrumb('features.company_details_set.edit', $id);

        return $this->view(($id ? 'Edit' : 'Create') . ' company details set', 'admin.features.company_details_set.edit', [
            'set' => $companyDetails,
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
            /** @var FeatureCompanyDetailsSet $set */
            $this->em->remove($set = $this->repository->findById($id));
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            return \Redirect::back()->withErrors(sprintf('Company details set %s used by some sets!', $id));
        }

        return \Redirect::route('admin::features.company_details_set.index')
            ->with('message', sprintf('Company details set \'%s\' removed!', $set->getName()));
    }
}
