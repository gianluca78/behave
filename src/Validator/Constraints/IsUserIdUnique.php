<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsUserIdUnique extends Constraint {
    public $message = 'The student id "{{ string }}" is already used';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}