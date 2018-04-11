<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DurationItemRepository")
 */
class DurationItem extends Item
{
    /**
     * @var integer $observationLengthInMinutes
     *
     * @ORM\Column(name="observation_length_in_minutes", type="integer")
     */
    private $observationLengthInMinutes;

    /**
     * @var Observation $observation
     *
     * @ORM\ManyToOne(targetEntity="Observation", inversedBy="durationItems")
     * @ORM\JoinColumn(name="observation_id", referencedColumnName="id")
     */
    private $observation;

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