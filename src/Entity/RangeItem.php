<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RangeItemRepository")
 */
class RangeItem extends Item
{
    /**
     * @var int $min
     *
     * @ORM\Column(name="min", type="integer")
     */
    private $min;

    /**
     * @var int $max
     *
     * @ORM\Column(name="max", type="integer")
     */
    private $max;

    /**
     * @var int $step
     *
     * @ORM\Column(name="step", type="integer")
     */
    private $step;

    /**
     * @var Measure $measure
     *
     * @ORM\ManyToOne(targetEntity="Measure", inversedBy="rangeItems")
     * @ORM\JoinColumn(name="measure_id", referencedColumnName="id")
     */
    private $measure;

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

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param int $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param int $step
     */
    public function setStep($step)
    {
        $this->step = $step;
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
