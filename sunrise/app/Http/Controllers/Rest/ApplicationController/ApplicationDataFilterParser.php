<?php namespace App\Http\Controllers\Rest\ApplicationController;

use App\Entities\Scholarship;
use App\Entities\ScholarshipField;
use Doctrine\ORM\QueryBuilder;

class ApplicationDataFilterParser
{
    /**
     * @var Scholarship
     */
    protected $scholarship;

    /**
     * ApplicationDataFilterParser constructor.
     * @param Scholarship $scholarship
     */
    public function __construct(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;
    }

    /**
     * @param QueryBuilder $qb
     * @param $filter
     * @return QueryBuilder
     */
    public function applyFilter(QueryBuilder $qb, $filter)
    {
        if (is_array($filter)) {
            foreach ($this->scholarship->getFields() as $field) {
                $key = 'data.' . $field->getField()->getId();
                if (array_key_exists($key, $filter)) {
                    $this->processSearchFilter($qb, $field, $filter[$key]);
                }
            }
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param ScholarshipField $field
     * @param $value
     * @return $this
     */
    protected function processSearchFilter(QueryBuilder $qb, ScholarshipField $field, $value)
    {
        if (is_array($value) && array_key_exists('search', $value)) {
            $id = $field->getField()->getId();
            $alias = $qb->getRootAliases()[0];
            $param = "data_param_$id";
            $search = $value['search'];
            $qb->andWhere(sprintf("JSON_SEARCH($alias.data, 'one', :%s, NULL, '$.$id') IS NOT NULL", $param))
                ->setParameter($param, "%$search%");
        }

        return $this;
    }
}