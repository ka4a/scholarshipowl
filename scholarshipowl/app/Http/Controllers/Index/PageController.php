<?php namespace App\Http\Controllers\Index;

use App\Entity\Page;
use App\Entity\PageOfferWall;
use App\Entity\Repository\EntityRepository;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;

class PageController extends BaseController
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * PageController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $em->getRepository(Page::class);
    }

    /**
     * @param Request $request
     * @param string  $page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function page(Request $request, $page)
    {
        /** @var Page $page */
        if ($page = $this->repository->findOneBy(['path' => $page])) {
            switch ($page->getType()) {
                case Page::TYPE_OFFER_WALL:
                    return $this->offerWallPageAction($request, $page);
                    break;
                default:
                    break;
            }
        }

        return abort(404);
    }

    /**
     * @param Request $request
     * @param Page    $page
     *
     * @return \Illuminate\Contracts\View\View
     */
    protected function offerWallPageAction(Request $request, Page $page)
    {
        /** @var PageOfferWall $offerWall */
        $offerWall = $this->em->createQueryBuilder()
            ->select(['ow', 'b1', 'b2', 'b3', 'b4', 'b5', 'b6', 'bi1', 'bi2', 'bi3', 'bi4', 'bi5', 'bi6'])
            ->from(PageOfferWall::class, 'ow')
            ->leftJoin('ow.banner1', 'b1')
            ->leftJoin('b1.image',   'bi1')
            ->leftJoin('ow.banner2', 'b2')
            ->leftJoin('b2.image',   'bi2')
            ->leftJoin('ow.banner3', 'b3')
            ->leftJoin('b3.image',   'bi3')
            ->leftJoin('ow.banner4', 'b4')
            ->leftJoin('b4.image',   'bi4')
            ->leftJoin('ow.banner5', 'b5')
            ->leftJoin('b5.image',   'bi5')
            ->leftJoin('ow.banner6', 'b6')
            ->leftJoin('b6.image',   'bi6')
            ->where('ow.page = :page')
            ->setParameter('page', $page)
            ->getQuery()
            ->getSingleResult();

        abort_if(null === $page, 404);

        return $this->getCommonUserViewModel('pages.offer-wall', [
            'extends'   => $request->get('full', false) ? 'base' : 'base-landing',
            'offerWall' => $offerWall,
            'page'      => $page,
        ])->send();
    }
}
