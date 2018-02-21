<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var ArrayCollection $durationItems
     *
     * @ORM\OneToMany(targetEntity="DurationItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $durationItems;
    
    /**
     * @var ArrayCollection $frequencyItems
     *
     * @ORM\OneToMany(targetEntity="FrequencyItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $frequencyItems;

    /**
     * @var ArrayCollection $integerItems
     *
     * @ORM\OneToMany(targetEntity="IntegerItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $integerItems;

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
    
    public function __construct()
    {
        $this->choiceItems = new ArrayCollection();
        $this->durationItems = new ArrayCollection();
        $this->frequencyItems = new ArrayCollection();
        $this->integerItems = new ArrayCollection();
        $this->rangeItems = new ArrayCollection();
        $this->textItems = new ArrayCollection();
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

    /**
     * Add durationItem
     *
     * @param \App\Entity\DurationItem $durationItem
     *
     * @return Observation
     */
    public function addDurationItem(\App\Entity\DurationItem $durationItem)
    {
        $this->durationItems[] = $durationItem;

        return $this;
    }

    /**
     * Remove durationItem
     *
     * @param \App\Entity\DurationItem $durationItem
     */
    public function removeDurationItem(\App\Entity\DurationItem $durationItem)
    {
        $this->durationItems->removeElement($durationItem);
    }

    /**
     * Get durationItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDurationItems()
    {
        return $this->durationItems;
    }

    /**
     * Add frequencyItem
     *
     * @param \App\Entity\FrequencyItem $frequencyItem
     *
     * @return Observation
     */
    public function addFrequencyItem(\App\Entity\FrequencyItem $frequencyItem)
    {
        $this->frequencyItems[] = $frequencyItem;

        return $this;
    }

    /**
     * Remove frequencyItem
     *
     * @param \App\Entity\FrequencyItem $frequencyItem
     */
    public function removeFrequencyItem(\App\Entity\FrequencyItem $frequencyItem)
    {
        $this->frequencyItems->removeElement($frequencyItem);
    }

    /**
     * Get frequencyItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFrequencyItems()
    {
        return $this->frequencyItems;
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

}
