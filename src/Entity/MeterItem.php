<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeterItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MeterItem extends Item implements JsonSerializable
{
    /**
     * @var string $xValue
     *
     * @ORM\Column(name="x_value", type="integer", length=255)
     */
    private $xValue;

    /**
     * @var string $yValue
     *
     * @ORM\Column(name="y_value", type="integer", length=255)
     */
    private $yValue;

    /**
     * @var string $labelY
     *
     * @ORM\Column(name="label_y", type="encrypted_string", length=255)
     */
    private $labelY;

    /**
     * @var string $labelMaxY
     *
     * @ORM\Column(name="label_max_y", type="encrypted_string", length=255)
     */
    private $labelMaxY;

    /**
     * @var string $labelMinY
     *
     * @ORM\Column(name="label_min_y", type="encrypted_string", length=255)
     */
    private $labelMinY;

    /**
     * @var string $labelX
     *
     * @ORM\Column(name="label_x", type="encrypted_string", length=255)
     */
    private $labelX;

    /**
     * @var string $labelMaxX
     *
     * @ORM\Column(name="label_max_x", type="encrypted_string", length=255)
     */
    private $labelMaxX;

    /**
     * @var string $labelMinX
     *
     * @ORM\Column(name="label_min_x", type="encrypted_string", length=255)
     */
    private $labelMinX;
    
    /**
     * @var Measure $measure
     *
     * @ORM\ManyToOne(targetEntity="Measure", inversedBy="meterItems")
     * @ORM\JoinColumn(name="measure_id", referencedColumnName="id")
     */
    private $measure;

    public function __sleep()
    {
        return array('label', 'positionNumber', 'xValue', 'yValue', 'labelY', 'labelMaxY', 'labelMinY', 'labelX',
            'labelMaxX', 'labelMinX');
    }

    public function jsonSerialize() {
        $meter = new \stdClass();
        $meter->label = $this->getLabel();
        $meter->xValue = $this->getXValue();
        $meter->yValue = $this->getYValue();
        $meter->labelY = $this->getLabelY();
        $meter->labelMaxY = $this->getLabelMaxY();
        $meter->labelMinY = $this->getLabelMinY();
        $meter->labelX = $this->getLabelX();
        $meter->labelMaxX = $this->getLabelMaxX();
        $meter->labelMinX = $this->getLabelMinX();
        $meter->positionNumber = $this->getPositionNumber();

        return $meter;
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
    public function getXValue()
    {
        return $this->xValue;
    }

    /**
     * @param string $xValue
     */
    public function setXValue($xValue)
    {
        $this->xValue = $xValue;
    }

    /**
     * @return string
     */
    public function getYValue()
    {
        return $this->yValue;
    }

    /**
     * @param string $yValue
     */
    public function setYValue($yValue)
    {
        $this->yValue = $yValue;
    }

    /**
     * @param string $labelMaxX
     */
    public function setLabelMaxX($labelMaxX)
    {
        $this->labelMaxX = $labelMaxX;
    }

    /**
     * @return string
     */
    public function getLabelMaxX()
    {
        return $this->labelMaxX;
    }

    /**
     * @param string $labelMaxY
     */
    public function setLabelMaxY($labelMaxY)
    {
        $this->labelMaxY = $labelMaxY;
    }

    /**
     * @return string
     */
    public function getLabelMaxY()
    {
        return $this->labelMaxY;
    }

    /**
     * @param string $labelMinX
     */
    public function setLabelMinX($labelMinX)
    {
        $this->labelMinX = $labelMinX;
    }

    /**
     * @return string
     */
    public function getLabelMinX()
    {
        return $this->labelMinX;
    }

    /**
     * @param string $labelMinY
     */
    public function setLabelMinY($labelMinY)
    {
        $this->labelMinY = $labelMinY;
    }

    /**
     * @return string
     */
    public function getLabelMinY()
    {
        return $this->labelMinY;
    }

    /**
     * @param string $labelX
     */
    public function setLabelX($labelX)
    {
        $this->labelX = $labelX;
    }

    /**
     * @return string
     */
    public function getLabelX()
    {
        return $this->labelX;
    }

    /**
     * @param string $labelY
     */
    public function setLabelY($labelY)
    {
        $this->labelY = $labelY;
    }

    /**
     * @return string
     */
    public function getLabelY()
    {
        return $this->labelY;
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
