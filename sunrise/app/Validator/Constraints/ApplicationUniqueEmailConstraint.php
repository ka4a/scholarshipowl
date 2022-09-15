<?php namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class ApplicationUniqueEmailConstraint extends Constraint
{
    public $message = 'Email  {{ email }} already applied.';
}
