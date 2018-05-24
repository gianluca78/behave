<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DirectObservationItemRepository")
 * @AppAssert\IsMultiple
 */
class DirectObservationItem extends Item
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $intervalLengthInSeconds;

    /**
     * @ORM\Column(type="integer")
     */
    private $observationLengthInMinutes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typology;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Measure", inversedBy="directObservationItems")
     * @ORM\JoinColumn(name="measure_id", referencedColumnName="id")
     */
    private $measure;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    public function getIntervalLengthInSeconds()
    {
        return $this->intervalLengthInSeconds;
    }

    public function setIntervalLengthInSeconds(int $intervalLengthInSeconds)
    {
        $this->intervalLengthInSeconds = $intervalLengthInSeconds;

        return $this;
    }

    public function getObservationLengthInMinutes()
    {
        return $this->observationLengthInMinutes;
    }

    public function setObservationLengthInMinutes(int $observationLengthInMinutes)
    {
        $this->observationLengthInMinutes = $observationLengthInMinutes;

        return $this;
    }

    public function getTypology()
    {
        return $this->typology;
    }

    public function setTypology(string $typology)
    {
        $this->typology = $typology;

        return $this;
    }

    /**
     * Set measure
     *
     * @param \App\Entity\Measure $measure
     *
     * @return Measure
     */
    public function setMeasure(\App\Entity\Measure $measure = null)
    {
        $this->measure = $measure;

        return $this;
    }

    /**
     * Get measure
     *
     * @return \App\Entity\Measure
     */
    public function getMeasure()
    {
        return $this->measure;
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
}
