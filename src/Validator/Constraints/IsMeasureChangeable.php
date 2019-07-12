<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsMeasureChangeable extends Constraint {
    public $message = 'You can\'t change the measure: data has been already collected.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}