<?php namespace App\Http\Controllers\Admin;

use App\Entity\Banner;
use App\Entity\Cms;
use App\Entity\Page;
use App\Entity\PageOfferWall;
use App\Entity\Repository\EntityRepository;
use App\Services\CmsService;
use Doctrine\ORM\EntityManager;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class CmsController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var CmsService
     */
    protected $service;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var EntityRepository
     */
    protected $pagesRepo;

    /**
     * @var EntityRepository
     */
    protected $bannersRepo;

    /**
     * @var Router
     */
    protected $route;

    /**
     * @var array
     */
    protected static $rules = [
        'page' => 'required',
        'url'  => 'required',
        'title' => 'required',
        'description' => 'required',
        'keywords' => 'required',
    ];

    /**
     * CmsController constructor.
     *
     * @param EntityManager $em
     * @param Router        $router
     * @param CmsService    $service
     */
    public function __construct(EntityManager $em, Router $router, CmsService $service)
    {
        parent::__construct();

        $this->em = $em;
        $this->service = $service;
        $this->repository = $em->getRepository(Cms::class);
        $this->pagesRepo = $em->getRepository(Page::class);
        $this->bannersRepo = $em->getRepository(Banner::class);
        $this->route = $router;
    }

    /**
     * List of all CMS pages
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function pagesAction()
    {
        $this->addBreadcrumb('CMS Pages', 'cms.pages');

        return $this->view('Cms Pages', 'admin.cms.pages', [
            'cms' => $this->repository->findAll(),
            'pages' => $this->pagesRepo->findAll(),
        ]);
    }

    /**
     * Create new CMS page configuration
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $this->addBreadcrumb('CMS Pages', 'cms.pages');
        $this->addPostBreadcrumb('cms.create');

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, static::$rules);

            $page = new Cms(
                $request->get('url'),
                $request->get('page'),
                $request->get('author', ''),
                $request->get('title'),
                $request->get('description'),
                $request->get('keywords')
            );

            $this->em->persist($page);
            $this->em->flush();
            $this->service->clear();

            return \Redirect::route('admin::cms.edit', $page->getCmsId())->with([
                'message' => 'Route successfuly created!',
            ]);
        }

        return $this->view('Cms Edit Page', 'admin.cms.edit', ['urls' => $this->getUnsetCmsRoutes()]);
    }

    /**
     * Edit CMS page
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function editAction(Request $request, $id)
    {
        $this->addBreadcrumb('CMS Pages', 'cms.pages');
        $this->addPostBreadcrumb('cms.edit', $id);

        /** @var Cms $page */
        $page = $this->repository->findById($id);

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, static::$rules);

            $page->setPage($request->get('page'));
            $page->setUrl($request->get('url'));
            $page->setTitle($request->get('title'));
            $page->setDescription($request->get('description'));
            $page->setKeywords($request->get('keywords'));
            $page->setAuthor($request->get('author', ''));

            $this->em->flush();
            $this->service->clear();

            return \Response::redirectToRoute('admin::cms.pages')->with([
                'message' => sprintf('Page %s successfuly saved!', $page->getPage()),
            ]);
        }

        return $this->view('Cms Edit Page', 'admin.cms.edit', ['page' => $page]);
    }

    /**
     * @param Request $request
     * @param null    $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function editPageAction(Request $request, $id = null)
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, [
                'path'        => 'required',
                'type'        => 'required|numeric',
                'title'       => 'string',
                'description' => 'string',
                'keywords'    => 'string',
                'author'      => 'string',
            ]);

            /** @var Page $page */
            $page = $id ? $this->pagesRepo->findById($id) : new Page();
            $page->setPath($request->get('path'));
            $page->setType($request->get('type'));
            $page->setTitle($request->get('title'));
            $page->setDescription($request->get('description'));
            $page->setKeywords($request->get('keywords'));
            $page->setAuthor($request->get('author'));

            if ($id) {
                if ($page->getType() == Page::TYPE_OFFER_WALL) {
                    $this->validate($request, [
                        'title' => 'required',
                        'description' => 'required',
                    ]);

                    /** @var PageOfferWall $offerWall */
                    $offerWall = $this->em->getRepository(PageOfferWall::class)
                        ->findOneBy(['page' => $page]) ?: new PageOfferWall($page);

                    $offerWall->setTitle($request->get('offer-wall-title'));
                    $offerWall->setDescription($request->get('offer-wall-description'));
                    $offerWall->setBanner1($request->get('offer-wall-banner1') ?
                        $this->bannersRepo->find($request->get('offer-wall-banner1')) : null
                    );
                    $offerWall->setBanner2($request->get('offer-wall-banner2') ?
                        $this->bannersRepo->find($request->get('offer-wall-banner2')) : null
                    );
                    $offerWall->setBanner3($request->get('offer-wall-banner3') ?
                        $this->bannersRepo->find($request->get('offer-wall-banner3')) : null
                    );
                    $offerWall->setBanner4($request->get('offer-wall-banner4') ?
                        $this->bannersRepo->find($request->get('offer-wall-banner4')) : null
                    );
                    $offerWall->setBanner5($request->get('offer-wall-banner5') ?
                        $this->bannersRepo->find($request->get('offer-wall-banner5')) : null
                    );
                    $offerWall->setBanner6($request->get('offer-wall-banner6') ?
                        $this->bannersRepo->find($request->get('offer-wall-banner6')) : null
                    );

                    $this->em->persist($offerWall);
                }
            }

            $this->em->persist($page);
            $this->em->flush();

            return \Redirect::route('admin::pages.edit', $page->getId())->with([
                'message' => 'Page saved!',
            ]);
        }

        $this->addBreadcrumb('Pages', 'cms.pages');
        $this->addPostBreadcrumb('pages.edit', $id);

        /** @var Page $page */
        $page = $id ? $this->pagesRepo->findById($id) : null;
        $data = ['page' => $page];

        if ($page && $page->isOfferWall()) {
            $data['banners'] = ['No Banner'];

            foreach ($this->bannersRepo->findAll() as $banner) {
                /** @var Banner $banner */
                $data['banners'][$banner->getId()] = $banner->getTitle();
            }

            /** @var PageOfferWall $offerWall */
            $data['offerWall'] = $this->em->getRepository(PageOfferWall::class)
                ->findOneBy(['page' => $page]) ?: new PageOfferWall($page);
        }

        return $this->view('Page - Edit', 'admin.cms.pages.edit', $data);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePageAction($id)
    {
        $this->em->remove($this->pagesRepo->findById($id));
        $this->em->flush();

        return \Redirect::route('admin::cms.pages')->with([
            'message' => 'Page deleted!',
        ]);
    }

    /**
     * @return array
     */
    protected function getUnsetCmsRoutes()
    {
        $set = $this->getSetCmsRoutes();
        $unset = [];

        /** @var Route $route */
        foreach ($this->route->getRoutes() as $route) {
            if (!empty($route->parameterNames())) {
                continue;
            }
            if (!in_array(Request::METHOD_GET, $route->methods())) {
                continue;
            }
            if (strpos($route->uri(), 'admin') === 0) {
                continue;
            }
            if (strpos($route->uri(), 'api') === 0) {
                continue;
            }
            if (strpos($route->uri(), 'apply-me') === 0) {
                continue;
            }
            if (strpos($route->uri(), 'rest') === 0) {
                continue;
            }

            if (in_array($url = $this->service->formatUrl($route->uri()), $set)) {
                continue;
            }

            $unset[$url] = $url;
        }

        ksort($unset);
        return $unset;
    }

    /**
     * @return mixed
     */
    protected function getSetCmsRoutes()
    {
         return array_map(
            function(Cms $page) {
                return $page->getUrl();
            },
            $this->repository->findAll()
        );
    }
}
