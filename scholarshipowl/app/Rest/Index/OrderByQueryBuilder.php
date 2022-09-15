<?php namespace App\Rest\Index;

use Doctrine\ORM\QueryBuilder;
use Illuminate\Foundation\Validation\ValidatesRequests;

class OrderByQueryBuilder extends AbstractChainMember
{
    use ValidatesRequests;

    /**
     * Default builder priority
     */
    const DEFAULT_PRIORITY = 500;

    /**
     * HTTP Param name to accept one sorting by
     */
    const PARAM_SORT_BY = 'sort_by';

    /**
     * HTTP Param name to accept one sorting direction
     */
    const PARAM_SORT_DIR = 'sort_direction';

    /**
     * HTTP Param name to accept arrays
     */
    const PARAM_SORT_ARR = 'sort';

    /**
     * @inheritdoc
     */
    public function handle(QueryBuilder $qb) : QueryBuilder
    {
        $sortBy = [];

        /**
         * Allow sending order by in JSON format
         */
        if (($sorting = @json_decode($this->request->get(static::PARAM_SORT_ARR), true)) && is_array($sorting)) {
            $this->request->attributes->set(static::PARAM_SORT_ARR, $sorting);
        }

        if ($this->request->get(static::PARAM_SORT_BY)) {
            $sortBy[$this->request->get(static::PARAM_SORT_BY)] = $this->request->get(static::PARAM_SORT_DIR, 'ASC');
        }

        $this->validate($this->request, [static::PARAM_SORT_ARR . '.*.property' => 'required']);
        if ($sorting = $this->request->get(static::PARAM_SORT_ARR)) {
            foreach ($sorting as $sort) {
                $sortBy[$sort['property']] = $sort['direction'] ?? 'ASC';
            }
        }

        if (($aliases = $qb->getRootAliases()) && !isset($aliases[0])) {
            throw new \LogicException("Applying order by before setting aliases!");
        }

        foreach ($sortBy as $property => $direction) {
            $qb->addOrderBy(sprintf('%s.%s', $aliases[0], $property), $direction);
        }

        return $qb;
    }
}
