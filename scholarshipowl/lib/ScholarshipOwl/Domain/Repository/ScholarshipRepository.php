<?php

namespace ScholarshipOwl\Domain\Repository;

use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;
use ScholarshipOwl\Data\Service\IDDL;
use ScholarshipOwl\Domain\Repository\Association\ScholarshipAssociation;

class ScholarshipRepository extends AbstractRepository
{

    protected $tableName = IDDL::TABLE_SCHOLARSHIP;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setEntityClass("\\ScholarshipOwl\\Data\\Entity\\Scholarship\\Scholarship");
    }

    /**
     * @param array $filters
     * @return Scholarship[]
     */
    public function findAllWithEligibilities(array $filters = array())
    {
        $scholarships = $this->findAll($filters);

        $eligibilityAssociation = new ScholarshipAssociation();
        $eligibilityAssociation->appendEligibilities($scholarships);

        return $scholarships;
    }

}
