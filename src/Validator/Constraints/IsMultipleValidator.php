<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsMultipleValidator extends ConstraintValidator {

    public function validate($directObservationItem, Constraint $constraint)
    {
        if(is_object($directObservationItem)) {
            $observationLengthInSeconds = $directObservationItem->getObservationLengthInMinutes() * 60;

            if ($directObservationItem->getIntervalLengthInSeconds() &&
                ($observationLengthInSeconds % $directObservationItem->getIntervalLengthInSeconds() != 0)) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('intervalLengthInSeconds')
                    ->setParameter('{{ string }}', $directObservationItem->getIntervalLengthInSeconds())
                    ->addViolation();
            }
        }
    }
} 