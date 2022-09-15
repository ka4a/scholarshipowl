<?php namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates that organisation created by properly authorized entity.
 */
class ApplicationScholarshipPolicyConstraintValidator extends ConstraintValidator
{
    /**
     * @param int $value
     * @param Constraint|ApplicationScholarshipPolicyConstraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ scholarshipId }}', $value)
            ->addViolation();
    }
}
