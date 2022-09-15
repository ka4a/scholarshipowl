<?php namespace App\Rest\Filter;

use App\Entity\MarketingSystemAccountData;
use App\Rest\Index\AbstractChainMember;
use Illuminate\Foundation\Validation\ValidatesRequests;
use ScholarshipOwl\Doctrine\ORM\ChainMemberInterface;
use Doctrine\ORM\QueryBuilder;
use App\Rest\Index\SimpleFiltersQueryBuilder;

class MarketingDataFilter extends AbstractChainMember
{
    const DEFAULT_PRIORITY = 10000;

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function handle(QueryBuilder $qb) : QueryBuilder
    {
        $request = $this->getRequest();
        $this->validate($request, [
            'filter_marketing.*.name'     => 'required',
            'filter_marketing.*.value'    => 'required',
            'filter_marketing.*.operator' => 'required',
        ]);

        if (!empty($filters = $request->get('filter_marketing', []))) {
            $marketingQuery = $qb->getEntityManager()->createQueryBuilder()
                ->select('DISTINCT IDENTITY(data.account)')
                ->from(MarketingSystemAccountData::class, 'data');

            foreach ($filters as $key => $filter) {
                $qb->setParameter($name = "filter_marketing_name_$key", $filter['name']);
                $qb->setParameter($value = "filter_marketing_value_$key", $filter['value']);
                $marketingQuery->setParameter($name = "filter_marketing_name_$key", $filter['name']);
                $marketingQuery->setParameter($value = "filter_marketing_value_$key", $filter['value']);
                $marketingQuery->orWhere(
                    $qb->expr()->andX(
                        $qb->expr()->eq('data.name', ":$name"),
                        SimpleFiltersQueryBuilder::operatorExpr('data.value', $filter['operator'], ":$value")
                    )
                );
            }

            $qb->andWhere($qb->expr()->in('subscription.account', $marketingQuery->getQuery()->getDQL()));
        }

        return $qb;
    }
}
