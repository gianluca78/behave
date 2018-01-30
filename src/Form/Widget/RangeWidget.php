<?php

namespace App\Form\Widget;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

use Symfony\Component\Validator\Constraints\Range;

class RangeWidget implements WidgetInterface {

    private $label;
    private $min;
    private $max;
    private $step;
    private $value;

    public function addField(FormBuilderInterface $formBuilderInterface)
    {
        $formBuilderInterface->add(
            $this->label,
            RangeType::class,
            array(
                'attr' => array(
                    'min' => $this->min,
                    'max' => $this->max,
                    'step' => $this->step,
                    'value' => $this->value
                ),
                'constraints' => array(
                    new Range(
                        array(
                            'min' => $this->min,
                            'max' => $this->max,
                            'minMessage' => 'The minimum allowed value is {{ limit }}',
                            'maxMessage' => 'The maximum allowed value is {{ limit }}'
                        )
                    )
                )
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
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param mixed $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * @return mixed
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param mixed $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * @return mixed
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param mixed $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}