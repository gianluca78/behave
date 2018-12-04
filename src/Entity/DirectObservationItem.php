<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DirectObservationItemRepository")
 * @AppAssert\IsMultiple*
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $feedbackForIntervalRecording;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Measure", inversedBy="directObservationItems")
     * @ORM\JoinColumn(name="measure_id", referencedColumnName="id")
     */
    private $measure;

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

        if(($this->getTypology() == 'whole-interval' ||
            $this->getTypology() == 'partial-interval' ||
            $this->getTypology() == 'momentary-time-sampling') &&
            (!$this->getFeedbackForIntervalRecording())
        ) {
            $context->buildViolation('Required field')
                ->atPath('feedbackForIntervalRecording')
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
     * @param mixed $feedbackForIntervalRecording
     */
    public function setFeedbackForIntervalRecording($feedbackForIntervalRecording)
    {
        $this->feedbackForIntervalRecording = $feedbackForIntervalRecording;
    }

    /**
     * @return mixed
     */
    public function getFeedbackForIntervalRecording()
    {
        return $this->feedbackForIntervalRecording;
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
