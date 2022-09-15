<?php

/**
 * Auto-generated rest repository class
 */

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ScholarshipTemplate;
use Pz\Doctrine\Rest\RestRepository;

class IframeRepository extends RestRepository
{
    /**
     * @param $template
     * @return array
     */
    public function findByTemplate($template)
    {
        return $this->createQueryBuilder('i')
            ->where('i.template = :template')
            ->setParameter('template', $template)
            ->getQuery()
            ->getResult();
    }
}
