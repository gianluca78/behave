<?php
namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsUserIdUniqueValidator extends ConstraintValidator {

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($student, Constraint $constraint)
    {
        $repository = $this->entityManager->getRepository('App:Student');

        $existingStudent = $repository->findOneByStudentId($student->getStudentId());

        if ($existingStudent && $existingStudent->getId() != $student->getId()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('studentId')
                ->setParameter('{{ string }}', $student->getStudentId())
                ->addViolation();
        }
    }
} 