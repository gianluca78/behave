<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObservationSchedulerRepository")
 */
class ObservationScheduler
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $hasDates
     *
     * @ORM\Column(name="has_dates", type="boolean")
     */
    private $hasDates;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $timeRangeStartTime;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $timeRangeEndTime;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $exactTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weeklyNumberOfWeeks;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $weeklyDaysOfWeek;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $repeatEndAfterNumberOfOccurrences;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $repeatEndDate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Observation", inversedBy="observationScheduler", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $observation;

    /**
     * @ORM\Column(type="integer")
     */
    private $repeatOption;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeOption;

    /**
     * @ORM\Column(type="integer")
     */
    private $repeatEndOption;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if($this->getHasDates()) {
            if(!$this->getStartDate()) {
                $context->buildViolation('Required field')
                    ->atPath('startDate')
                    ->addViolation();
            }

            if($this->getTimeOption() == 1 && $this->getTimeRangeStartTime() >= $this->getTimeRangeEndTime()) {
                $context->buildViolation('The start time must be before the end time')
                    ->atPath('timeRangeStartTime')
                    ->addViolation();
            }

            if($this->getWeeklyNumberOfWeeks() && $this->getWeeklyNumberOfWeeks() <= 0 || !$this->getWeeklyNumberOfWeeks()) {
                $context->buildViolation('This value must be a positive integer')
                    ->atPath('weeklyNumberOfWeeks')
                    ->addViolation();
            }

            if($this->getRepeatOption() == 1 && count($this->getWeeklyDaysOfWeek()) == 0) {
                $context->buildViolation('This value must be a positive integer')
                    ->atPath('weeklyDaysOfWeek')
                    ->addViolation();
            }

            if($this->getRepeatEndOption() === '0' && $this->getRepeatEndAfterNumberOfOccurrences() <= 0) {
                $context->buildViolation('This value must be a positive integer')
                    ->atPath('repeatEndAfterNumberOfOccurrences')
                    ->addViolation();
            }

            if($this->getRepeatEndOption() === '1' && $this->getRepeatEndDate() <= $this->getStartDate()) {
                $context->buildViolation('The end date must follow the start date')
                    ->atPath('repeatEndDate')
                    ->addViolation();
            }
        }

    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $hasDates
     */
    public function setHasDates($hasDates)
    {
        $this->hasDates = $hasDates;
    }

    /**
     * @return string
     */
    public function getHasDates()
    {
        return $this->hasDates;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getTimeRangeStartTime()
    {
        return $this->timeRangeStartTime;
    }

    public function setTimeRangeStartTime(?\DateTimeInterface $timeRangeStartTime)
    {
        $this->timeRangeStartTime = $timeRangeStartTime;

        return $this;
    }

    public function getTimeRangeEndTime()
    {
        return $this->timeRangeEndTime;
    }

    public function setTimeRangeEndTime(?\DateTimeInterface $timeRangeEndTime)
    {
        $this->timeRangeEndTime = $timeRangeEndTime;

        return $this;
    }

    public function getExactTime()
    {
        return $this->exactTime;
    }

    public function setExactTime(?\DateTimeInterface $exactTime)
    {
        $this->exactTime = $exactTime;

        return $this;
    }

    public function getWeeklyNumberOfWeeks()
    {
        return $this->weeklyNumberOfWeeks;
    }

    public function setWeeklyNumberOfWeeks(int $weeklyNumberOfWeeks)
    {
        $this->weeklyNumberOfWeeks = $weeklyNumberOfWeeks;

        return $this;
    }

    public function getWeeklyDaysOfWeek()
    {
        return $this->weeklyDaysOfWeek;
    }

    public function setWeeklyDaysOfWeek(array $weeklyDaysOfWeek)
    {
        $this->weeklyDaysOfWeek = $weeklyDaysOfWeek;

        return $this;
    }

    public function getRepeatEndAfterNumberOfOccurrences()
    {
        return $this->repeatEndAfterNumberOfOccurrences;
    }

    public function setRepeatEndAfterNumberOfOccurrences(int $repeatEndAfterNumberOfOccurrences)
    {
        $this->repeatEndAfterNumberOfOccurrences = $repeatEndAfterNumberOfOccurrences;

        return $this;
    }

    public function getRepeatEndDate()
    {
        return $this->repeatEndDate;
    }

    public function setRepeatEndDate($repeatEndDate)
    {
        $this->repeatEndDate = $repeatEndDate;

        return $this;
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

    public function getRepeatOption(): ?int
    {
        return $this->repeatOption;
    }

    public function setRepeatOption(int $repeatOption): self
    {
        $this->repeatOption = $repeatOption;

        return $this;
    }

    public function getTimeOption(): ?int
    {
        return $this->timeOption;
    }

    public function setTimeOption(int $timeOption): self
    {
        $this->timeOption = $timeOption;

        return $this;
    }

    public function getRepeatEndOption(): ?int
    {
        return $this->repeatEndOption;
    }

    public function setRepeatEndOption(int $repeatEndOption): self
    {
        $this->repeatEndOption = $repeatEndOption;

        return $this;
    }

}
