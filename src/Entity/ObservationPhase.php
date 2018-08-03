<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObservationPhaseRepository")
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

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
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
}
