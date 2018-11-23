<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ObservationRepository")
 */
class Observation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var bool $isEnabled
     *
     * @ORM\Column(name="is_enabled", type="boolean", nullable=true)
     */
    private $isEnabled;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="encrypted_string", length=255)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="encrypted_text", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ObservationDate", mappedBy="observation", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $observationDates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ObservationPhase", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $observationPhases;

    /**
     * @ORM\Column(name="observer_user_id", type="encrypted_string", length=255)
     */
    private $observerUserId;

    /**
     * @ORM\Column(name="observer_username", type="encrypted_string", length=255)
     */
    private $observerUsername;

    /**
     * @ORM\Column(name="creator_user_id", type="encrypted_string", length=255)
     */
    private $creatorUserId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Student", inversedBy="observations")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Measure", inversedBy="observations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $measure;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ObservationScheduler", mappedBy="observation", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $observationScheduler;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSingleCaseDesign;

    public function __construct()
    {
        $this->observationDates = new ArrayCollection();
        $this->observationPhases = new ArrayCollection();
    }

    public function getSlugIsEnabled()
    {
        return ($this->isEnabled) ? 'Yes' : 'No';
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
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param mixed $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $observerUserId
     */
    public function setObserverUserId($observerUserId)
    {
        $this->observerUserId = $observerUserId;
    }

    /**
     * @return mixed
     */
    public function getObserverUserId()
    {
        return $this->observerUserId;
    }

    /**
     * @return Collection|ObservationDate[]
     */
    public function getObservationDates()
    {
        return $this->observationDates;
    }

    public function addObservationDate(ObservationDate $observationDate)
    {
        if (!$this->observationDates->contains($observationDate)) {
            $this->observationDates[] = $observationDate;
            $observationDate->setObservation($this);
        }

        return $this;
    }

    public function removeObservationDate(ObservationDate $observationDate)
    {
        if ($this->observationDates->contains($observationDate)) {
            $this->observationDates->removeElement($observationDate);
            // set the owning side to null (unless already changed)
            if ($observationDate->getObservation() === $this) {
                $observationDate->setObservation(null);
            }
        }

        return $this;
    }

    public function resetObservationDates()
    {
        $this->observationDates->clear();

        return $this;
    }

    public function isDateIncluded(\DateTime $dateTime)
    {
        if($this->getObservationScheduler() && !$this->getObservationScheduler()->getHasDates()) {
            return true;
        }

        foreach($this->getObservationDates() as $observationDate) {
            $dateStart = $observationDate->getStartDateTimestamp();
            $dateEnd = $observationDate->getEndDateTimestamp();

            if($dateTime >= $dateStart && $dateTime <= $dateEnd) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection|ObservationPhase[]
     */
    public function getObservationPhases()
    {
        return $this->observationPhases;
    }

    public function addObservationPhase(ObservationPhase $observationPhase)
    {
        if (!$this->observationPhases->contains($observationPhase)) {
            $this->observationPhases[] = $observationPhase;
            $observationPhase->setObservation($this);
        }

        return $this;
    }

    public function removeObservationPhase(ObservationPhase $observationPhase)
    {
        if ($this->observationPhases->contains($observationPhase)) {
            $this->observationPhases->removeElement($observationPhase);
            // set the owning side to null (unless already changed)
            if ($observationPhase->getObservation() === $this) {
                $observationPhase->setObservation(null);
            }
        }

        return $this;
    }

    public function getStudent()
    {
        return $this->student;
    }

    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @param mixed $observerUsername
     */
    public function setObserverUsername($observerUsername)
    {
        $this->observerUsername = $observerUsername;
    }

    /**
     * @return mixed
     */
    public function getObserverUsername()
    {
        return $this->observerUsername;
    }

    /**
     * @param mixed $creatorUserId
     */
    public function setCreatorUserId($creatorUserId)
    {
        $this->creatorUserId = $creatorUserId;
    }

    /**
     * @return mixed
     */
    public function getCreatorUserId()
    {
        return $this->creatorUserId;
    }

    public function getMeasure()
    {
        return $this->measure;
    }

    public function setMeasure($measure)
    {
        $this->measure = $measure;

        return $this;
    }

    public function getObservationScheduler()
    {
        return $this->observationScheduler;
    }

    public function setObservationScheduler(ObservationScheduler $observationScheduler)
    {
        $this->observationScheduler = $observationScheduler;

        // set the owning side of the relation if necessary
        if ($this !== $observationScheduler->getObservation()) {
            $observationScheduler->setObservation($this);
        }

        return $this;
    }

    public function getIsSingleCaseDesign()
    {
        return $this->isSingleCaseDesign;
    }

    public function setIsSingleCaseDesign(bool $isSingleCaseDesign)
    {
        $this->isSingleCaseDesign = $isSingleCaseDesign;

        return $this;
    }
}
