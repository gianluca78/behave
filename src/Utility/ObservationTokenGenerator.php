<?php
namespace App\Utility;

use Doctrine\ORM\EntityManagerInterface;

class ObservationTokenGenerator {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate()
    {
        $token = sha1(random_bytes(10));

        if($this->entityManager->getRepository('App\Entity\Observation')->findByToken($token)) {
            $this->generate();
        }

        return $token;
    }
} 