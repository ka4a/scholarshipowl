<?php namespace App\Repositories;

use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipTemplateSubscription;
use Pz\Doctrine\Rest\RestRepository;

/**
 * Class ScholarshipTemplateSubscriptionRepository
 */
class ScholarshipTemplateSubscriptionRepository extends RestRepository
{
    /**
     * @param ScholarshipTemplate $template
     * @param string $email
     * @return null|ScholarshipTemplateSubscription
     */
    public function findOneByTemplateAndEmail(ScholarshipTemplate $template, $email)
    {
        return $this->findOneBy(['template' => $template, 'email' => $email]);
    }

    /**
     * @param ScholarshipTemplate $template
     * @return \Doctrine\ORM\Query
     */
    public function queryWaitingByTemplate(ScholarshipTemplate $template)
    {
        return $this->createQueryBuilder('s')
            ->where('s.template = :template AND s.status = :waiting')
            ->setParameter('waiting', ScholarshipTemplateSubscription::STATUS_WAITING)
            ->setParameter('template', $template)
            ->getQuery();
    }
}
