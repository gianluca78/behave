<?php

namespace App\Form\Widget;

use Symfony\Component\Form\FormBuilderInterface;
use App\Form\Type\Custom\BehavioralRecordingItemType;
use Symfony\Component\Translation\TranslatorInterface;

class BehavioralRecordingWidget implements WidgetInterface {

    private $label;
    private $observationLengthInMinutes;
    private $partialLengthInSeconds;
    private $typology;
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
            BehavioralRecordingItemType::class,
            array(
                'attr' => array(
                    'value' => $this->value
                ),
                'counter_value' => 0,
                'label' => $this->label,
                'observation_length_in_minutes' => $this->observationLengthInMinutes,
                'partial_length_in_seconds' => $this->partialLengthInSeconds,
                'typology' => $this->typology
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
     * @param mixed $partialLengthInSeconds
     */
    public function setPartialLengthInSeconds($partialLengthInSeconds)
    {
        $this->partialLengthInSeconds = $partialLengthInSeconds;
    }

    /**
     * @return mixed
     */
    public function getPartialLengthInSeconds()
    {
        return $this->partialLengthInSeconds;
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
     * @param mixed $typology
     */
    public function setTypology($typology)
    {
        $this->typology = $typology;
    }

    /**
     * @return mixed
     */
    public function getTypology()
    {
        return $this->typology;
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
}