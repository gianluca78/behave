<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BehavioralRecordingItemRepository")
 */
class BehavioralRecordingItem extends Item
{
    /**
     * @var integer $partialLengthInSeconds
     *
     * @ORM\Column(name="partial_length_in_seconds", type="integer", nullable=true)
     */
    private $partialLengthInSeconds;

    /**
     * @var integer $observationLengthInSeconds
     *
     * @ORM\Column(name="observation_length_in_seconds", type="integer")
     */
    private $observationLengthInMinutes;

    /**
     * @var string $typology;
     *
     * @ORM\Column(type="string", length=255)
     */
    private $typology;

    /**
     * @var Observation $observation
     *
     * @ORM\ManyToOne(targetEntity="Observation", inversedBy="behavioralRecordingItems")
     * @ORM\JoinColumn(name="observation_id", referencedColumnName="id")
     */
    private $observation;

    /**
     * @param int $partialLengthInSeconds
     */
    public function setPartialLengthInSeconds($partialLengthInSeconds)
    {
        $this->partialLengthInSeconds = $partialLengthInSeconds;
    }

    /**
     * @return int
     */
    public function getPartialLengthInSeconds()
    {
        return $this->partialLengthInSeconds;
    }

    /**
     * @return int
     */
    public function getObservationLengthInMinutes()
    {
        return $this->observationLengthInMinutes;
    }

    /**
     * @param int $observationLengthInMinutes
     */
    public function setObservationLengthInMinutes($observationLengthInMinutes)
    {
        $this->observationLengthInMinutes = $observationLengthInMinutes;
    }



    /**
     * @param mixed $typology
     */
    public function setTypology($typology)
    {
        $this->typology = $typology;
    }

    /**
     * @return mixed
     */
    public function getTypology()
    {
        return $this->typology;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPositionNumber()
    {
        return $this->positionNumber;
    }

    /**
     * @param mixed $positionNumber
     */
    public function setPositionNumber($positionNumber)
    {
        $this->positionNumber = $positionNumber;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Set survey
     *
     * @param \App\Entity\Observation $observation
     *
     * @return Observation
     */
    public function setObservation(\App\Entity\Observation $observation = null)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return \App\Entity\Observation
     */
    public function getObservation()
    {
        return $this->observation;
    }
}