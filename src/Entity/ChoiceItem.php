<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChoiceItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ChoiceItem extends Item
{
    /**
     * @var string $emptyValue
     *
     * @ORM\Column(name="empty_value", type="encrypted_string", nullable=true, length=255)
     */
    private $emptyValue;

    /**
     * @var bool $isExpanded
     *
     * @ORM\Column(name="is_expanded", type="boolean", nullable=true)
     */
    private $isExpanded;

    /**
     * @var bool $isMultiple
     *
     * @ORM\Column(name="is_multiple", type="boolean", nullable=true)
     */
    private $isMultiple;

    /**
     * @var array $options
     *
     * @ORM\Column(name="options", type="array")
     */
    private $options;

    /**
     * @var Observation $observation
     *
     * @ORM\ManyToOne(targetEntity="Observation", inversedBy="choiceItems")
     * @ORM\JoinColumn(name="observation_id", referencedColumnName="id")
     */
    private $observation;

    /**
     * @return string
     */
    public function getEmptyValue()
    {
        return $this->emptyValue;
    }

    /**
     * @param string $emptyValue
     */
    public function setEmptyValue($emptyValue)
    {
        $this->emptyValue = $emptyValue;
    }

    /**
     * @return boolean
     */
    public function getIsExpanded()
    {
        return $this->isExpanded;
    }

    /**
     * @param boolean $isSelect
     */
    public function setIsExpanded($isExpanded)
    {
        $this->isExpanded = $isExpanded;
    }

    /**
     * @return boolean
     */
    public function getIsMultiple()
    {
        return $this->isMultiple;
    }

    /**
     * @param boolean $isMultiple
     */
    public function setIsMultiple($isMultiple)
    {
        $this->isMultiple = $isMultiple;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return Observation
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * @param Observation $observation
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
