<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObservationDateRepository")
 */
class ObservationDate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDateTimestamp;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDateTimestamp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Observation", inversedBy="observationDates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $observation;

    public function getId()
    {
        return $this->id;
    }

    public function getStartDateTimestamp()
    {
        return $this->startDateTimestamp;
    }

    public function setStartDateTimestamp(\DateTimeInterface $startDateTimestamp)
    {
        $this->startDateTimestamp = $startDateTimestamp;

        return $this;
    }

    public function getEndDateTimestamp()
    {
        return $this->endDateTimestamp;
    }

    public function setEndDateTimestamp(\DateTimeInterface $endDateTimestamp)
    {
        $this->endDateTimestamp = $endDateTimestamp;

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
}
