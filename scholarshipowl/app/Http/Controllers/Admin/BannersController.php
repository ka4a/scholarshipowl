<?php namespace App\Http\Controllers\Admin;

use App\Entity\BannerImage;
use App\Entity\PageOfferWall;
use App\Entity\Repository\EntityRepository;
use Doctrine\ORM\EntityManager;
use App\Entity\Banner;
use Doctrine\ORM\Query;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class BannersController extends BaseController
{
    use ValidatesRequests;

    const DELETE_FORCE = 'delete_force';
    const DELETE_FORCE_PAGES = 'delete_force_pages';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * BannersController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $em->getRepository(Banner::class);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function indexAction(Request $request)
    {
        $this->addBreadcrumb('Banners - List', 'marketing.banners.index');

        return $this->view('Banners - List', 'admin.marketing.banners.index', [
            'banners' => $this->repository->findAll(),
            'deleteForce' => $request->get(self::DELETE_FORCE),
            'deleteForcePages' => $request->get(self::DELETE_FORCE_PAGES),
        ]);
    }

    /**
     * @param Request $request
     * @param null    $id
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editAction(Request $request, $id = null)
    {
        $this->addBreadcrumb('Banners - List', 'marketing.banners.index');
        $this->addPostBreadcrumb('marketing.banners.edit', $id);

        /** @var Banner $banner */
        $banner = $id ? $this->repository->find($id) : null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, [
                'type'           => 'required',
                'title'          => 'required',
                'url'            => 'required|url',
                'url_display'    => 'required',
                'image'          => 'image|dimensions:width=300,height=250',
                'header_content' => 'string',
                'text'           => 'string',
            ]);

            if (!$banner) {
                $this->em->persist($banner = new Banner());
            }

            $banner->setType($request->get('type'));
            $banner->setTitle($request->get('title'));
            $banner->setUrl($request->get('url'));
            $banner->setUrlDisplay($request->get('url_display'));
            $banner->setHeaderContent($request->get('header_content'));
            $banner->setText($request->get('text'));

            $this->em->flush($banner);

            if ($request->hasFile('image')) {
                if ($image = $banner->getImage()) {
                    $this->em->remove($image);
                }

                $this->em->persist(new BannerImage($banner, $request->file('image')));
            }

            $this->em->flush();

            return \Redirect::route('admin::marketing.banners.edit', $banner->getId())->with([
                'message' => 'Banner saved!',
            ]);
        }

        return $this->view('Banners - Edit', 'admin.marketing.banners.edit', [
            'banner' => $banner,
        ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAction($id, $force = null)
    {
        /** @var Banner $banner */
        $banner = $this->repository->findById($id);

        $offerWallsWithBanners = $this->em->getRepository(PageOfferWall::class)
            ->createQueryBuilder('pow')
            ->orWhere('pow.banner1 = :banner')
            ->orWhere('pow.banner2 = :banner')
            ->orWhere('pow.banner3 = :banner')
            ->orWhere('pow.banner4 = :banner')
            ->orWhere('pow.banner5 = :banner')
            ->orWhere('pow.banner6 = :banner')
            ->getQuery()
            ->setParameter('banner', $banner)
            ->getResult();

        if (!empty($offerWallsWithBanners)) {

            if ($force === null) {
                return \Redirect::route('admin::marketing.banners.index', [
                    self::DELETE_FORCE => $id,
                    self::DELETE_FORCE_PAGES => array_map(
                        function (PageOfferWall $banner) {
                            return $banner->getPage()->getId();
                        },
                        $offerWallsWithBanners
                    ),
                ]);
            }

            /**
             * Remove banner from offer wall page
             */
            $this->em->createQuery(sprintf('UPDATE %s pow SET pow.banner1 = NULL WHERE pow.banner1 = :banner', PageOfferWall::class))
                ->execute(['banner' => $banner->getId()]);
            $this->em->createQuery(sprintf('UPDATE %s pow SET pow.banner2 = NULL WHERE pow.banner2 = :banner', PageOfferWall::class))
                ->execute(['banner' => $banner->getId()]);
            $this->em->createQuery(sprintf('UPDATE %s pow SET pow.banner3 = NULL WHERE pow.banner3 = :banner', PageOfferWall::class))
                ->execute(['banner' => $banner->getId()]);
            $this->em->createQuery(sprintf('UPDATE %s pow SET pow.banner4 = NULL WHERE pow.banner4 = :banner', PageOfferWall::class))
                ->execute(['banner' => $banner->getId()]);
            $this->em->createQuery(sprintf('UPDATE %s pow SET pow.banner5 = NULL WHERE pow.banner5 = :banner', PageOfferWall::class))
                ->execute(['banner' => $banner->getId()]);
            $this->em->createQuery(sprintf('UPDATE %s pow SET pow.banner6 = NULL WHERE pow.banner6 = :banner', PageOfferWall::class))
                ->execute(['banner' => $banner->getId()]);
        }

        $this->em->remove($banner);
        $this->em->flush();

        return \Redirect::route('admin::marketing.banners.index')->with([
            'message' => 'Banner deleted!',
        ]);
    }
}
