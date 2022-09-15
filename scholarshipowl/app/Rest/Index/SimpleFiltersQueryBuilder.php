<?php namespace App\Rest\Index;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SimpleFiltersQueryBuilder extends AbstractChainMember
{
    const PROPERTY_NAME = 'property';
    const PROPERTY_VALUE = 'value';
    const PROPERTY_OPERATOR = 'operator';

    /**
     * Default builder priority
     */
    const DEFAULT_PRIORITY = 100;

    /**
     * HTTP Param name
     */
    const PARAM_FILTER = 'filter';

    /**
     * @var array
     */
    protected $aliasesJoins;

    /**
     * @var array|callable[]
     */
    protected $countQueries;

    /**
     * @param string $property
     * @param string $operator
     * @param mixed  $value
     *
     * @return Expr|Expr\Comparison|Expr\Func
     */
    static public function operatorExpr(string $property, string $operator, $value)
    {
        $expr = new Expr();

        switch ($operator) {
            case 'eq':
            case '=':
                return $expr->eq($property, $value);
                break;

            case 'gt':
            case '>':
                return $expr->gt($property, $value);
                break;

            case 'gte':
            case '>=':
                return $expr->gte($property, $value);
                break;

            case 'lt':
            case '<':
                return $expr->lt($property, $value);
                break;

            case 'lte':
            case '<=':
                return $expr->lte($property, $value);
                break;

            case 'in':
                return $expr->in($property, $value);
                break;

            case 'notIn':
                return $expr->notIn($property, $value);
                break;

            case 'like':
                return $expr->like($property, $value);
                break;

            case 'notLike':
                return $expr->notLike($property, $value);
                break;

            default:
                throw new \InvalidArgumentException(sprintf('Unknown operator "%s"!', $operator));
                break;
        }
    }


    /**
     * SimpleFiltersQueryBuilder constructor.
     *
     * @param \Illuminate\Http\Request|null $request
     * @param array                         $aliasesJoins
     * @param array                         $countQueries
     */
    public function __construct($request, array $aliasesJoins = [], array $countQueries = [])
    {
        parent::__construct($request);

        $this->aliasesJoins = $aliasesJoins;
        $this->countQueries = $countQueries;
    }

    /**
     * Apply simple filters on query builder
     *
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function handle(QueryBuilder $qb) : QueryBuilder
    {
        $this->validate($this->request, [
            static::PARAM_FILTER.'.*.property' => 'required',
            static::PARAM_FILTER.'.*.operator' => 'required',
            static::PARAM_FILTER.'.*.value'    => 'required',
        ]);

        if (($aliases = $qb->getAllAliases()) && !isset($aliases[0])) {
            throw new \LogicException("Applying simple filters before setting aliases!");
        }

        $count = 0;
        foreach ($this->request->get(static::PARAM_FILTER, []) as $filter) {
            $alias = strstr($filter[static::PROPERTY_NAME], '.', true);
            $property = $alias ?
                $filter[static::PROPERTY_NAME] :
                sprintf('%s.%s', $aliases[0], $filter[static::PROPERTY_NAME]);

            if ($alias && !(in_array($alias, $qb->getAllAliases()) || $this->initAlias($alias, $qb))) {
                throw new \InvalidArgumentException(sprintf('Unknown alias: %s', $alias));
            }

            $param = 'fsimple' . preg_replace("/[^a-zA-Z0-9]+/", "", $property) . $count;

            switch ($filter[static::PROPERTY_OPERATOR]) {
                case 'countEq':
                case 'countLt':
                case 'countGt':
                case 'countLte':
                case 'countGte':
                    $this->countQuery($qb, $property, $filter[static::PROPERTY_OPERATOR], ":$param");
                    break;

                default:
                    $qb->andWhere(static::operatorExpr($property, $filter[static::PROPERTY_OPERATOR], ":$param"));
                    break;
            }

            $qb->setParameter($param, $filter[static::PROPERTY_VALUE]);
            $count++;
        }

        return $qb;
    }

    /**
     * @param string       $alias
     * @param QueryBuilder $qb
     *
     * @return bool
     */
    protected function initAlias(string $alias, QueryBuilder $qb)
    {
        if ($callback = $this->aliasesJoins[$alias] ?? null) {
            if (!is_callable($callback)) {
                throw new \InvalidArgumentException('Alias join callback is not callback!');
            }

            return $callback($qb);
        }

        return false;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $property
     * @param string       $operator
     *
     * @return mixed
     */
    protected function countQuery(QueryBuilder $qb, string $property, string $operator, string $param)
    {
        if (null === ($callback = $this->countQueries[$property] ?? null)) {
            throw new \InvalidArgumentException(sprintf('Count query not found for: %s', $property));
        }

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Alias join callback is not callback!');
        }

        return $callback($qb, strtolower(str_after('count', $operator)), $param);
    }
}
