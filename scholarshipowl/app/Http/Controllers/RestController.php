<?php namespace App\Http\Controllers;

use App\Entity\Account;
use App\Entity\Exception\EntityNotFound;
use App\Entity\Repository\EntityRepository;
use App\Entity\Subscription;
use App\Rest\Requests\RestRequest;
use App\Http\Traits\JsonResponses;

use App\Rest\Index\LimitAndStartQueryBuilder;
use App\Rest\Index\OrderByQueryBuilder;
use App\Rest\Index\SimpleFiltersQueryBuilder;

use App\Rest\Traits\RestAuthorization;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Illuminate\Auth\AuthenticationException;
use ScholarshipOwl\Data\ResourceInterface;
use ScholarshipOwl\Data\ResourceCollection;
use ScholarshipOwl\Doctrine\ORM\QueryBuilderChain;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\QueryBuilder;

abstract class RestController extends Controller
{
    use JsonResponses;
    use RestAuthorization;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * RestController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityRepository
     */
    abstract protected function getRepository();

    /**
     * Get base query builder. All next filters should be applied on this query builder.
     *
     * @param Request $request
     *
     * @return QueryBuilder
     */
    abstract protected function getBaseIndexQuery(Request $request);

    /**
     * Get base query for scalar count result.
     *
     * @param Request $request
     *
     * @return QueryBuilder
     */
    abstract protected function getBaseIndexCountQuery(Request $request);

    /**
     * @return ResourceInterface
     */
    abstract protected function getResource();

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('index', $this->getRepository()->getClassName());

        $dataQuery = $this->getBaseIndexQueryBuilderChain($request)
            ->add(new LimitAndStartQueryBuilder($request))
            ->process($this->getBaseIndexQuery($request));

        $countQuery = $this->getBaseIndexQueryBuilderChain($request)
            ->process($this->getBaseIndexCountQuery($request));

        return $this->jsonIndexResponse($dataQuery, $countQuery, $this->getResource());
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->authorize('show', $entity = $this->findById($id));

        return $this->jsonResponse($entity);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->authorize('destroy', $entity = $this->findById($id));
        $this->em->remove($entity);
        $this->em->flush();

        return $this->jsonSuccessResponse();
    }

    /**
     * @param Request      $request
     *
     * @return QueryBuilderChain
     */
    protected function getBaseIndexQueryBuilderChain(Request $request)
    {
        $chain = new QueryBuilderChain();
        $chain->add(new SimpleFiltersQueryBuilder(
            $request, $this->getIndexAliasesJoins($request), $this->getIndexCountQueries($request))
        );
        $chain->add(new OrderByQueryBuilder($request));

        return $chain;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    protected function getIndexAliasesJoins(Request $request) : array
    {
        return [];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    protected function getIndexCountQueries(Request $request) : array
    {
        return [];
    }

    /**
     * Should be used for retrieving base entity (resource).
     *
     * @param mixed         $id
     * @param string|null   $entity
     *
     * @return object
     * @throws NotFoundHttpException
     */
    protected function findById($id, $entity = null)
    {
        try {
            $repository = $entity !== null ? $this->em->getRepository($entity) : $this->getRepository();
            return $repository->findById($id);
        } catch (EntityNotFound $e) {
            throw new NotFoundHttpException('Entity not found.', $e);
        }
    }

    /**
     * @param QueryBuilder           $dataQuery
     * @param QueryBuilder           $countQuery
     * @param ResourceInterface|null $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonIndexResponse(
        QueryBuilder $dataQuery,
        QueryBuilder $countQuery,
        ResourceInterface $resource
    ) {
        return $this->jsonResponse(
            $dataQuery->getQuery()->setHint(Query::HINT_REFRESH, true)->getResult(),
            [
                'count' => (int) $countQuery->getQuery()->getSingleScalarResult(),
                'start' => (int) $dataQuery->getFirstResult(),
                'limit' => (int) $dataQuery->getMaxResults(),
            ],
            $resource
        );
    }

    /**
     * @param null|array $data
     * @param null $meta
     * @param ResourceInterface|null $resource
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse($data = null, $meta = null, ResourceInterface $resource = null, $headers = [])
    {
        $resource = $resource ?: $this->getResource();

        if (is_array($data)) {
            $data = (new ResourceCollection($resource, $data))->toArray();
        } elseif (is_object($data)) {
            $data = $resource->setEntity($data)->toArray();
        }

        return $this->jsonDataResponse($data, $meta, $headers);
    }

    /**
     * @param object      $entity
     * @param RestRequest $request
     * @param array       $exclude
     *
     * @return array
     */
    protected function updateEntity($entity, RestRequest $request, array $exclude = [])
    {
        $updated = [];

        foreach (array_keys($request->rules()) as $input) {
            $input = strpos($input, '.') ? str_before('.', $input) : $input;

            if (in_array($input, $exclude) || !$request->exists($input)) {
                continue;
            }

            if (method_exists($entity, $method = 'set' . ucfirst($input))) {
                $entity->$method($request->get($input));

                $updated[] = $input;
            }
        }

        return $updated;
    }
}
