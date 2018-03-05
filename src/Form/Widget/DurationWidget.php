<?php

namespace App\Form\Widget;

use Symfony\Component\Form\FormBuilderInterface;
use App\Form\Type\Custom\DurationItemType;
use Symfony\Component\Translation\TranslatorInterface;

class DurationWidget extends FrequencyWidget {

    private $label;
    private $observationLengthInMinutes;
    private $translator;
    private $value;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function addField(FormBuilderInterface $formBuilderInterface, $name)
    {
        $formBuilderInterface->add(
            $name,
            DurationItemType::class,
            array(
                'attr' => array(
                    'value' => $this->value
                ),
                'counter_value' => 0,
                'label' => $this->label,
                'observation_length_in_minutes' => $this->observationLengthInMinutes
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
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param mixed $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * @return mixed
     */
    public function getObservationLengthInMinutes()
    {
        return $this->observationLengthInMinutes;
    }

    /**
     * @param mixed $observationLengthInMinutes
     */
    public function setObservationLengthInMinutes($observationLengthInMinutes)
    {
        $this->observationLengthInMinutes = $observationLengthInMinutes;
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