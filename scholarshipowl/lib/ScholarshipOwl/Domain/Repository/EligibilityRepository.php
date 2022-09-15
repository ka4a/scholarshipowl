<?php

namespace ScholarshipOwl\Domain\Repository;

use ScholarshipOwl\Data\Entity\Scholarship\Eligibility;
use ScholarshipOwl\Data\Service\IDDL;

class EligibilityRepository extends AbstractRepository
{

    protected $tableName = IDDL::TABLE_ELIGIBILITY;

    public function __construct()
    {
        parent::__construct();
        $this->setEntityClass("\\ScholarshipOwl\\Data\\Entity\\Scholarship\\Eligibility");
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getBaseQuery()
    {
        return \DB::table($this->tableName)
            ->join(IDDL::TABLE_FIELD, 'field.field_id', '=', 'eligibility.field_id')
            ->select(array('eligibility.*', 'field.name AS field_name'));
    }

}