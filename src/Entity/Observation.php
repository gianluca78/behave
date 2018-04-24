<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @var ArrayCollection $choiceItems
     *
     * @ORM\OneToMany(targetEntity="ChoiceItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $choiceItems;
    
    /**
     * @var ArrayCollection $behavioralRecordingItems
     *
     * @ORM\OneToMany(targetEntity="BehavioralRecordingItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $behavioralRecordingItems;
    
    /**
     * @var ArrayCollection $integerItems
     *
     * @ORM\OneToMany(targetEntity="IntegerItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $integerItems;

    /**
     * @var ArrayCollection $meterItems
     *
     * @ORM\OneToMany(targetEntity="MeterItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $meterItems;    
    
    /**
     * @var ArrayCollection $rangeItems
     *
     * @ORM\OneToMany(targetEntity="RangeItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $rangeItems;

    /**
     * @var ArrayCollection $textItems
     *
     * @ORM\OneToMany(targetEntity="TextItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $textItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ObservationDate", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $observationDates;
    
    public function __construct()
    {
        $this->choiceItems = new ArrayCollection();
        $this->behavioralRecordingItems = new ArrayCollection();
        $this->integerItems = new ArrayCollection();
        $this->meterItems = new ArrayCollection();
        $this->rangeItems = new ArrayCollection();
        $this->textItems = new ArrayCollection();
        $this->observationDates = new ArrayCollection();
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
     * Add choiceItem
     *
     * @param \App\Entity\ChoiceItem $choiceItem
     *
     * @return Observation
     */
    public function addChoiceItem(\App\Entity\ChoiceItem $choiceItem)
    {
        $this->choiceItems[] = $choiceItem;

        return $this;
    }

    /**
     * Remove choiceItem
     *
     * @param \App\Entity\ChoiceItem $choiceItem
     */
    public function removeChoiceItem(\App\Entity\ChoiceItem $choiceItem)
    {
        $this->choiceItems->removeElement($choiceItem);
    }

    /**
     * Get itemsChoice
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChoiceItems()
    {
        return $this->choiceItems;
    }

    /**
     * Add behavioralRecordingItem
     *
     * @param \App\Entity\BehavioralRecordingItem $behavioralRecordingItem
     *
     * @return Observation
     */
    public function addBehavioralRecordingItem(\App\Entity\BehavioralRecordingItem $behavioralRecordingItem)
    {
        $this->behavioralRecordingItems[] = $behavioralRecordingItem;

        return $this;
    }

    /**
     * Remove behavioralRecordingItem
     *
     * @param \App\Entity\BehavioralRecordingItem $behavioralRecordingItem
     */
    public function removeBehavioralRecordingItem(\App\Entity\BehavioralRecordingItem $behavioralRecordingItem)
    {
        $this->behavioralRecordingItems->removeElement($behavioralRecordingItem);
    }

    /**
     * Get behavioralRecordingItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBehavioralRecordingItems()
    {
        return $this->behavioralRecordingItems;
    }

    /**
     * Add integerItem
     *
     * @param \App\Entity\IntegerItem $integerItem
     *
     * @return Observation
     */
    public function addIntegerItem(\App\Entity\IntegerItem $integerItem)
    {
        $this->integerItems[] = $integerItem;

        return $this;
    }

    /**
     * Remove integerItem
     *
     * @param \App\Entity\IntegerItem $integerItem
     */
    public function removeIntegerItem(\App\Entity\IntegerItem $integerItem)
    {
        $this->integerItems->removeElement($integerItem);
    }

    /**
     * Get integerItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIntegerItems()
    {
        return $this->integerItems;
    }

    /**
     * Add meterItem
     *
     * @param \App\Entity\MeterItem $meterItem
     *
     * @return Observation
     */
    public function addMeterItem(\App\Entity\MeterItem $meterItem)
    {
        $this->meterItems[] = $meterItem;

        return $this;
    }

    /**
     * Remove meterItem
     *
     * @param \App\Entity\MeterItem $meterItem
     */
    public function removeMeterItem(\App\Entity\MeterItem $meterItem)
    {
        $this->meterItems->removeElement($meterItem);
    }

    /**
     * Get itemsMeter
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeterItems()
    {
        return $this->meterItems;
    }

    /**
     * Add textRangeItem
     *
     * @param \App\Entity\RangeItem $textRangeItem
     *
     * @return Observation
     */
    public function addRangeItem(\App\Entity\RangeItem $textRangeItem)
    {
        $this->rangeItems[] = $textRangeItem;

        return $this;
    }

    /**
     * Remove textRangeItem
     *
     * @param \App\Entity\RangeItem $textRangeItem
     */
    public function removeRangeItem(\App\Entity\RangeItem $textRangeItem)
    {
        $this->rangeItems->removeElement($textRangeItem);
    }

    /**
     * Get textFrequecyItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRangeItems()
    {
        return $this->rangeItems;
    }

    /**
     * Add textItem
     *
     * @param \App\Entity\TextItem $textItem
     *
     * @return Observation
     */
    public function addTextItem(\App\Entity\TextItem $textItem)
    {
        $this->textItems[] = $textItem;

        return $this;
    }

    /**
     * Remove textItem
     *
     * @param \App\Entity\TextItem $textItem
     */
    public function removeTextItem(\App\Entity\TextItem $textItem)
    {
        $this->textItems->removeElement($textItem);
    }

    /**
     * Get itemsText
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTextItems()
    {
        return $this->textItems;
    }

    public function countItems()
    {
        return $this->choiceItems->count() + $this->behavioralRecordingItems->count() +
            $this->integerItems->count() + $this->meterItems->count() + $this->rangeItems->count() +
            $this->textItems->count();
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

    public function isDateIncluded(\DateTime $dateTime)
    {
        foreach($this->getObservationDates() as $observationDate) {
            $dateStart = $observationDate->getStartDateTimestamp();
            $dateEnd = $observationDate->getEndDateTimestamp();

            if($dateTime >= $dateStart && $dateTime <= $dateEnd) {
                return true;
            }
        }

        return false;
    }
}
