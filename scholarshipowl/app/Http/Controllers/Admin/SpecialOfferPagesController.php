<?php namespace App\Http\Controllers\Admin;

use App\Entity\Package;
use App\Entity\Repository\EntityRepository;
use App\Entity\SpecialOfferPage;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class SpecialOfferPagesController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var EntityRepository
     */
    protected $packagesRepo;

    /**
     * SpecialOfferPagesController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $em->getRepository(SpecialOfferPage::class);
        $this->packagesRepo = $em->getRepository(Package::class);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function indexAction()
    {
        $this->addBreadcrumb('Special Offer Pages', 'cms.special-offer-pages.index');

        /** @var SpecialOfferPage[] $pages */
        $pages = $this->repository->findAll();

        return $this->view('Special Offer Pages', 'admin.cms.special_offer_pages.index', [
            'pages' => $pages,
        ]);
    }

    /**
     * @param Request $request
     * @param null    $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function editAction(Request $request, $id = null)
    {
        $this->addBreadcrumb('Special Offer Pages', 'cms.special-offer-pages.index');
        $this->addPostBreadcrumb('cms.special-offer-pages.edit', $id);

        /** @var SpecialOfferPage $page */
        $page = $id ? $this->repository->findById($id) : null;

        $data = [
            'page' => $page,
            'packages' => [
                'Please select',
            ],
        ];

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, [
                'packageId'        => 'required|exists:App\Entity\Package',
                'url'              => 'required|unique:App\Entity\SpecialOfferPage,url,' . $id,
                'title'            => 'required',
                'icon_title1'      => 'required',
                'icon_title2'      => 'required',
                'icon_title3'      => 'required',
                'description'      => 'required',
                'scroll_to_text'   => 'string',
                'meta_title'       => 'string',
                'meta_description' => 'string',
                'meta_keywords'    => 'string',
                'meta_author'      => 'string',
            ]);

            if (!$page) {
                $this->em->persist($page = new SpecialOfferPage());
            }

            $page->hydrate($request->all());
            $page->setPackage($this->packagesRepo->findById($request->get('packageId')));

            $this->em->flush();

            return \Redirect::route('admin::cms.special-offer-pages.edit', $page->getId())->with([
                'message' => 'Special offer page saved!',
            ]);
        }

        /** @var Package $package */
        foreach ($this->packagesRepo->findAll() as $package) {
            $data['packages'][$package->getPackageId()] = sprintf('(%s) %s', $package->getPackageId(), $package->getName());
        }

        return $this->view('Special Offer Page', 'admin.cms.special_offer_pages.edit', $data);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAction($id)
    {
        /** @var SpecialOfferPage $page */
        $this->em->remove($page = $this->repository->findById($id));
        $this->em->flush();

        return \Redirect::route('admin::cms.special-offer-pages.index')->with([
            'message' => sprintf('Page \'%s\' removed!', $page->getUrl()),
        ]);
    }
}
