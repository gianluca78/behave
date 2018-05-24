<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TextItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TextItem extends Item
{
    /**
     * @var string $fieldValue
     *
     * @ORM\Column(name="field_value", type="encrypted_string", length=255)
     */
    private $fieldValue;

    /**
     * @var string $placeholder
     *
     * @ORM\Column(type="encrypted_string", length=255)
     */
    private $placeholder;

    /**
     * @var Measure $measure
     *
     * @ORM\ManyToOne(targetEntity="Measure", inversedBy="textItems")
     * @ORM\JoinColumn(name="measure_id", referencedColumnName="id")
     */
    private $measure;

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
     * @return string
     */
    public function getFieldValue()
    {
        return $this->fieldValue;
    }

    /**
     * @param string $fieldValue
     */
    public function setFieldValue($fieldValue)
    {
        $this->fieldValue = $fieldValue;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
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

}
