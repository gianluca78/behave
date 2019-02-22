<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ObservationPhaseRepository")
 * @UniqueEntity({"name", "observation"})
 */
class ObservationPhase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var bool $isIntervention
     *
     * @ORM\Column(name="is_intervention", type="boolean", nullable=true)
     */
    private $isIntervention;

    /**
     * @var string $intervention
     *
     * @ORM\Column(name="intervention", type="encrypted_text", nullable=true)
     */
    private $intervention;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="encrypted_string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Observation", inversedBy="observationPhases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $observation;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $dataIds;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUnderPharmacologicalTreatment;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if($this->getIsIntervention() && !$this->getIntervention()
        ) {
            $context->buildViolation('Required field')
                ->atPath('intervention')
                ->addViolation();
        }
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsIntervention()
    {
        return $this->isIntervention;
    }

    public function setIsIntervention(bool $isIntervention)
    {
        $this->isIntervention = $isIntervention;

        return $this;
    }

    /**
     * @param string $intervention
     */
    public function setIntervention($intervention)
    {
        $this->intervention = $intervention;
    }

    /**
     * @return string
     */
    public function getIntervention()
    {
        return $this->intervention;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getObservation()
    {
        return $this->observation;
    }

    public function setObservation(Observation $observation)
    {
        $this->observation = $observation;

        return $this;
    }

    public function addDataId($dataId)
    {
        $this->dataIds[] = $dataId;
    }

    public function getDataIds()
    {
        return ($this->dataIds) ? $this->dataIds : array();
    }

    public function removeDataId($dataId)
    {
        if(is_array($this->dataIds) && in_array($dataId, $this->dataIds)) {
            $this->dataIds = array_diff($this->dataIds, array($dataId));
        }
    }

    public function setDataIds(array $dataIds)
    {
        $this->dataIds = $dataIds;

        return $this;
    }

    public function getIsUnderPharmacologicalTreatment(): ?bool
    {
        return $this->isUnderPharmacologicalTreatment;
    }

    public function setIsUnderPharmacologicalTreatment(bool $isUnderPharmacologicalTreatment): self
    {
        $this->isUnderPharmacologicalTreatment = $isUnderPharmacologicalTreatment;

        return $this;
    }
}
