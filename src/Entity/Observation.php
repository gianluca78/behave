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
     * @var ArrayCollection $textItems
     *
     * @ORM\OneToMany(targetEntity="TextItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $textItems;

    /**
     * @var ArrayCollection $frequencyItems
     *
     * @ORM\OneToMany(targetEntity="FrequencyItem", mappedBy="observation", cascade={"persist", "remove"})
     */
    private $frequencyItems;

    public function __construct()
    {
        $this->textItems = new ArrayCollection();
        $this->frequencyItems = new ArrayCollection();
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

}
