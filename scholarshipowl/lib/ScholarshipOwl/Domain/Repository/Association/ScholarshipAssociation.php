<?php

namespace ScholarshipOwl\Domain\Repository\Association;

use ScholarshipOwl\Data\Entity\Scholarship\Eligibility;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;
use ScholarshipOwl\Domain\Repository\EligibilityRepository;

class ScholarshipAssociation
{

    /**
     * @param Scholarship[] $scholarships
     * @return Scholarship[]
     */
    public function appendEligibilities(array $scholarships)
    {
        $eligibilityRepository = new EligibilityRepository();

        /** @var Eligibility[] $eligibilities */
        $eligibilities = $eligibilityRepository->findAll(array(
            array(
                'field' => 'scholarship_id',
                'operator' => 'in',
                'value' => array_map(function(Scholarship $scholarship) {
                    return $scholarship->getScholarshipId();
                }, $scholarships),
            )
        ));

        foreach ($scholarships as $scholarship) {
            $scholarship->setEligibilities(
                array_filter($eligibilities, function(Eligibility $eligibility) use ($scholarship) {
                    if ($eligibility->getRawData('scholarship_id') === $scholarship->getScholarshipId()) {
                        $eligibility->setScholarship($scholarship);
                        return true;
                    }

                    return false;
                })
            );
        }

        return $scholarships;
    }

}
