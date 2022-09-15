<?php namespace App\Repositories;

use App\Entities\ScholarshipWebsite;
use Pz\Doctrine\Rest\RestRepository;

class ScholarshipWebsiteRepository extends RestRepository
{
    /**
     * @param string $domain
     * @return ScholarshipWebsite|null
     */
    public function findByDomain($domain)
    {
        $qb = $this->createQueryBuilder('w');

        /**
         * Check if domain is not hosted. check without and with "www".
         */
        $notHostedDomainExpr = $qb->expr()->andX(
            $qb->expr()->eq('w.domainHosted', '0'),
            $qb->expr()->in('w.domain', [$domain, "www.$domain"])
        );

        /**
         * Check hosted domain contact the hosted domain.
         */
        $hostedDomainExpr = $qb->expr()->andX(
            $qb->expr()->eq('w.domainHosted', '1'),
            $qb->expr()->eq(
                $qb->expr()->concat(
                    'w.domain',
                    $qb->expr()->literal('.'),
                    $qb->expr()->literal(config('services.barn.hosted_domain'))
                ),
                ':domain'
            )
        );

        return $qb
            ->where($qb->expr()->orX($notHostedDomainExpr, $hostedDomainExpr))
            ->setParameter('domain', $domain)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
