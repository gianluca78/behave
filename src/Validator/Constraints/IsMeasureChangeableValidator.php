<?php
namespace App\Validator\Constraints;

use App\CouchDb\Client as CouchDbClient;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

class IsMeasureChangeableValidator extends ConstraintValidator {

    protected $em;
    protected $couchDbClient;

    public function __construct(EntityManagerInterface $em, CouchDbClient $couchDbClient)
    {
        $this->em = $em;
        $this->couchDbClient = $couchDbClient;
    }

    public function validate($object, Constraint $constraint)
    {
        $newValue = $object->getMeasure();

        $oldData = $this->em
            ->getUnitOfWork()
            ->getOriginalEntityData($object);

        if(isset($oldData['measure'])) {
            $data = $this->couchDbClient->getObservationsById($object->getId());
            $data = json_decode($data->getContents(), true)['rows'];

            if($newValue != $oldData['measure'] && !empty($data)) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('measure')
                    ->addViolation();
            }
        }
    }
} 