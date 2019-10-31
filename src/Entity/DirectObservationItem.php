<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use JsonSerializable;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DirectObservationItemRepository")
 * @AppAssert\IsMultiple*
 */
class DirectObservationItem extends Item implements JsonSerializable
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

    public function __sleep()
    {
        return array('label', 'positionNumber', 'intervalLengthInSeconds', 'observationLengthInMinutes', 'typology');
    }

    public function jsonSerialize() {
        $direct = new \stdClass();
        $direct->label = $this->getLabel();
        $direct->intervalLengthInSeconds = $this->getIntervalLengthInSeconds();
        $direct->observationLengthInMinutes = $this->getObservationLengthInMinutes();
        $direct->typology = $this->getTypology();
        $direct->positionNumber = $this->getPositionNumber();

        return $direct;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if(($this->getTypology() == 'whole-interval' ||
            $this->getTypology() == 'partial-interval' ||
            $this->getTypology() == 'momentary-time-sampling') &&
            (!$this->getIntervalLengthInSeconds())
        ) {
            $context->buildViolation('Required field')
                ->atPath('intervalLengthInSeconds')
                ->addViolation();
        }
    }

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

    public function setIntervalLengthInSeconds($intervalLengthInSeconds)
    {
        $this->intervalLengthInSeconds = $intervalLengthInSeconds;

        return $this;
    }

    public function getObservationLengthInMinutes()
    {
        return $this->observationLengthInMinutes;
    }

    public function setObservationLengthInMinutes($observationLengthInMinutes)
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
