<?php namespace App\Http\Controllers\Rest;

use App\Entity\Winner;
use App\Http\Controllers\Controller;
use App\Http\Misc\Paginator;
use App\Http\Traits\JsonResponses;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WinnerRestController extends Controller
{
    use JsonResponses;

    /**
     * Get list of winners
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index()
    {
	    $repo = \EntityManager::getRepository(Winner::class);
        $paginator = new Paginator(100);
        $dql = $repo->createQueryBuilder('w')
            ->where('w.published = 1');

        $totalCount = (int)(clone($dql))
            ->select('COUNT(w.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $result = $dql->setFirstResult($paginator->getOffset())
            ->setMaxResults($paginator->getLimit())
            ->getQuery()
            ->getResult();

        $meta = [];
        $meta["count"] = $totalCount;
        $meta["start"] = $paginator->getOffset();
        $meta["limit"] = $paginator->getLimit();

        return $this->jsonSuccessResponse($result, $meta);
    }

    /**
     * Get one winner
     *
     * @param Request $request
     * @param null $winnerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showWinner(Request $request, $winnerId = null)
    {
        $winner = \EntityManager::getRepository(Winner::class)
            ->findOneBy(['id' => $winnerId, 'published' => 1]);

        if ($winner) {
            return $this->jsonSuccessResponse($winner);
        }

        throw new NotFoundHttpException('Winner not found');
    }
}
