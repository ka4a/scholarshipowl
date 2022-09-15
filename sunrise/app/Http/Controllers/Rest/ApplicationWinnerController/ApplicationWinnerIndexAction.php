<?php namespace App\Http\Controllers\Rest\ApplicationWinnerController;

use App\Entities\ScholarshipTemplate;
use App\Http\Requests\RestRequest;
use App\Repositories\ScholarshipTemplateRepository;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Pz\Doctrine\Rest\Action\CollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;

class ApplicationWinnerIndexAction extends CollectionAction
{
    /**
     * @var array
     */
    protected $filterable = ['disqualifiedAt'];

    /**
     * @param RestRequestContract|RestRequest $request
     * @param QueryBuilder $qb
     * @return CollectionAction|self
     * @throws \Doctrine\ORM\ORMException
     */
    public function applyFilter(RestRequestContract $request, QueryBuilder $qb)
    {
        /**
         * Filter winners by scholarship template.
         * Example: ?filter=[][scholarship_template]=666
         */
        if (($filter = $request->getFilter())) {
            if (is_array($filter) && isset($filter['scholarship_template'])) {

                $template = $this->repository()
                    ->getEntityManager()
                    ->getReference(ScholarshipTemplate::class, $filter['scholarship_template']);

                Gate::authorize('restShow', $template);

                $qb->join(sprintf('%s.%s', $this->repository()->alias(), 'application'), 'a')
                    ->join('a.scholarship', 's')
                    ->join('s.template', 't')
                    ->andWhere('t.id = :template')
                    ->setParameter('template', $template);

                $qb->orderBy($this->repository()->alias() . '.id', 'asc');

            }
        }

        return parent::applyFilter($request, $qb);
    }
}
