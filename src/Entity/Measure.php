<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MeasureRepository")
 * @UniqueEntity(
 *      fields={"name", "creatorUserId"},
 *      message = "This value is already used"
 * )
 */
class Measure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="creator_user_id", type="string", length=255)
     */
    private $creatorUserId;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="encrypted_text")
     */
    private $description;

    /**
     * @var ArrayCollection $choiceItems
     *
     * @ORM\OneToMany(targetEntity="ChoiceItem", mappedBy="measure", cascade={"persist", "remove"})
     */
    private $choiceItems;

    /**
     * @var ArrayCollection $integerItems
     *
     * @ORM\OneToMany(targetEntity="IntegerItem", mappedBy="measure", cascade={"persist", "remove"})
     */
    private $integerItems;

    /**
     * @var ArrayCollection $meterItems
     *
     * @ORM\OneToMany(targetEntity="MeterItem", mappedBy="measure", cascade={"persist", "remove"})
     */
    private $meterItems;

    /**
     * @var ArrayCollection $rangeItems
     *
     * @ORM\OneToMany(targetEntity="RangeItem", mappedBy="measure", cascade={"persist", "remove"})
     */
    private $rangeItems;

    /**
     * @var ArrayCollection $textItems
     *
     * @ORM\OneToMany(targetEntity="TextItem", mappedBy="measure", cascade={"persist", "remove"})
     */
    private $textItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DirectObservationItem", mappedBy="measure", cascade={"persist", "remove"})
     * @Assert\Valid
     *
     */
    private $directObservationItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Observation", mappedBy="measure")
     */
    private $observations;

    public function __construct()
    {
        $this->choiceItems = new ArrayCollection();
        $this->integerItems = new ArrayCollection();
        $this->meterItems = new ArrayCollection();
        $this->rangeItems = new ArrayCollection();
        $this->textItems = new ArrayCollection();
        $this->directObservationItems = new ArrayCollection();
        $this->observations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if($this->countItems() == 0) {
            $context->buildViolation('You have to insert at least 1 item: click on the button "Add item" below to do it')
                ->atPath('name')
                ->addViolation();
        }

        if($this->observations->count() > 0) {
            $context->buildViolation('The measure is used in one or more observations. You can\'t edit it.')
                ->atPath('name')
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
     * @return Measure
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
     * Add integerItem
     *
     * @param \App\Entity\IntegerItem $integerItem
     *
     * @return Measure
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
     * @return Measure
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
     * @return Measure
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
     * @return Measure
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
        return $this->choiceItems->count() + $this->directObservationItems->count() +
        $this->integerItems->count() + $this->meterItems->count() + $this->rangeItems->count() +
        $this->textItems->count();
    }

    /**
     * @return Collection|DirectObservationItem[]
     */
    public function getDirectObservationItems()
    {
        return $this->directObservationItems;
    }

    public function addDirectObservationItem(DirectObservationItem $directObservationItem)
    {
        if (!$this->directObservationItems->contains($directObservationItem)) {
            $this->directObservationItems[] = $directObservationItem;
            $directObservationItem->setMeasure($this);
        }

        return $this;
    }

    public function removeDirectObservationItem(DirectObservationItem $directObservationItem)
    {
        if ($this->directObservationItems->contains($directObservationItem)) {
            $this->directObservationItems->removeElement($directObservationItem);
            // set the owning side to null (unless already changed)
            if ($directObservationItem->getMeasure() === $this) {
                $directObservationItem->setMeasure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Observation[]
     */
    public function getObservations()
    {
        return $this->observations;
    }

    public function addObservation(Observation $observation)
    {
        if (!$this->observations->contains($observation)) {
            $this->observations[] = $observation;
            $observation->setMeasure($this);
        }

        return $this;
    }

    public function removeObservation(Observation $observation)
    {
        if ($this->observations->contains($observation)) {
            $this->observations->removeElement($observation);
            // set the owning side to null (unless already changed)
            if ($observation->getMeasure() === $this) {
                $observation->setMeasure(null);
            }
        }

        return $this;
    }
}
