<?php

namespace App\Form\Widget;

use Symfony\Component\Form\FormBuilderInterface;
use App\Form\Type\Custom\MeterItemType;
use Symfony\Component\Translation\TranslatorInterface;

class MeterWidget implements WidgetInterface {

    private $label;
    private $translator;
    private $valueX;
    private $valueY;
    private $labelY;
    private $labelMaxY;
    private $labelMinY;
    private $labelX;
    private $labelMaxX;
    private $labelMinX;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function addField(FormBuilderInterface $formBuilderInterface)
    {
        $formBuilderInterface->add(
            $this->label,
            MeterItemType::class,
            array(
                'attr' => array(
                    'valueX' => $this->valueX,
                    'valueY' => $this->valueY,
                ),
                'label' => $this->label,
                'label_y' => $this->labelY,
                'label_min_y' => $this->labelMinY,
                'label_max_y' => $this->labelMaxY,
                'label_x' => $this->labelX,
                'label_min_x' => $this->labelMinX,
                'label_max_x' => $this->labelMaxX,
            )
        );

        return $formBuilderInterface;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getValueX()
    {
        return $this->valueX;
    }

    /**
     * @param mixed $valueX
     */
    public function setValueX($valueX)
    {
        $this->valueX = $valueX;
    }

    /**
     * @return mixed
     */
    public function getValueY()
    {
        return $this->valueY;
    }

    /**
     * @param mixed $valueY
     */
    public function setValueY($valueY)
    {
        $this->valueY = $valueY;
    }

    /**
     * @param mixed $labelMaxX
     */
    public function setLabelMaxX($labelMaxX)
    {
        $this->labelMaxX = $labelMaxX;
    }

    /**
     * @return mixed
     */
    public function getLabelMaxX()
    {
        return $this->labelMaxX;
    }

    /**
     * @param mixed $labelMaxY
     */
    public function setLabelMaxY($labelMaxY)
    {
        $this->labelMaxY = $labelMaxY;
    }

    /**
     * @return mixed
     */
    public function getLabelMaxY()
    {
        return $this->labelMaxY;
    }

    /**
     * @param mixed $labelMinX
     */
    public function setLabelMinX($labelMinX)
    {
        $this->labelMinX = $labelMinX;
    }

    /**
     * @return mixed
     */
    public function getLabelMinX()
    {
        return $this->labelMinX;
    }

    /**
     * @param mixed $labelMinY
     */
    public function setLabelMinY($labelMinY)
    {
        $this->labelMinY = $labelMinY;
    }

    /**
     * @return mixed
     */
    public function getLabelMinY()
    {
        return $this->labelMinY;
    }

    /**
     * @param mixed $labelX
     */
    public function setLabelX($labelX)
    {
        $this->labelX = $labelX;
    }

    /**
     * @return mixed
     */
    public function getLabelX()
    {
        return $this->labelX;
    }

    /**
     * @param mixed $labelY
     */
    public function setLabelY($labelY)
    {
        $this->labelY = $labelY;
    }

    /**
     * @return mixed
     */
    public function getLabelY()
    {
        return $this->labelY;
    }
}