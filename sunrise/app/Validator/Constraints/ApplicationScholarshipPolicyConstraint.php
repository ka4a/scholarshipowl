<?php namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class ApplicationScholarshipPolicyConstraint extends Constraint
{
    public $message = 'Not authorized to apply for {{ scholarshipId }}.';
}
