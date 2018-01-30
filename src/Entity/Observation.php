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
     * @var ArrayCollection $frequencyItems
     *
     * @ORM\OneToMany(targetEntity="FrequencyItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $frequencyItems;
    
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
        $this->frequencyItems = new ArrayCollection();
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
     * Add textFrequencyItem
     *
     * @param \App\Entity\FrequencyItem $textFrequencyItem
     *
     * @return Observation
     */
    public function addFrequencyItem(\App\Entity\FrequencyItem $textFrequencyItem)
    {
        $this->frequencyItems[] = $textFrequencyItem;

        return $this;
    }

    /**
     * Remove textFrequencyItem
     *
     * @param \App\Entity\FrequencyItem $textFrequencyItem
     */
    public function removeFrequencyItem(\App\Entity\FrequencyItem $textFrequencyItem)
    {
        $this->frequencyItems->removeElement($textFrequencyItem);
    }

    /**
     * Get textFrequecyItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFrequencyItems()
    {
        return $this->frequencyItems;
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
