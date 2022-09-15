<?php namespace App\Repositories;

use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use Doctrine\Common\Collections\ArrayCollection;
use Pz\Doctrine\Rest\RestRepository;

/**
 * Class ScholarshipTemplateRepository
 * @method ScholarshipTemplate findById($id)
 */
class ScholarshipTemplateRepository extends RestRepository
{
    /**
     * @param ScholarshipTemplate $template
     * @return ArrayCollection|ScholarshipTemplate[]
     */
    public function findPublished(ScholarshipTemplate $template)
    {
        return new ArrayCollection(
            $this->getEntityManager()
                ->createQueryBuilder()
                ->select('s')
                ->from(Scholarship::class, 's')
                ->where('s.template = :template AND s.expiredAt IS NULL')
                ->orderBy('s.expiredAt', 'DESC')
                ->setParameter('template', $template)
                ->getQuery()
                ->getResult()
        );
    }

}
