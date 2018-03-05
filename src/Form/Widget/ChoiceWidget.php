<?php

namespace App\Form\Widget;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;

class ChoiceWidget implements WidgetInterface {

    private $label;
    private $emptyValue;
    private $isExpanded;
    private $isMultiple;
    private $options;
    private $translator;

    CONST NOT_VALID_CHOICE = 'The value you selected is not a valid choice.';
    CONST NOT_VALID_MULTIPLE_CHOICE = 'One or more of the given values is invalid.';

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function addField(FormBuilderInterface $formBuilderInterface, $name)
    {
        $options = array(
            'choices' => $this->options,
            'expanded' => $this->isExpanded,
            'label' => $this->label,
            'multiple' => $this->isMultiple,
            'constraints' => array(
                new Choice(
                    array(
                        'choices' => $this->options,
                        'multiple' => $this->isMultiple,
                        'message' => $this->translator->trans(self::NOT_VALID_CHOICE),
                        'multipleMessage' => $this->translator->trans(self::NOT_VALID_MULTIPLE_CHOICE),
                    )
                )
            )
        );

        if($this->emptyValue) {
            $options['placeholder'] = $this->emptyValue;
        }

        $formBuilderInterface->add(
            $name,
            ChoiceType::class,
            $options
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
    public function getEmptyValue()
    {
        return $this->emptyValue;
    }

    /**
     * @param mixed $emptyValue
     */
    public function setEmptyValue($emptyValue)
    {
        $this->emptyValue = $emptyValue;
    }

    /**
     * @return mixed
     */
    public function getIsExpanded()
    {
        return $this->isExpanded;
    }

    /**
     * @param mixed $isExpanded
     */
    public function setIsExpanded($isExpanded)
    {
        $this->isExpanded = $isExpanded;
    }

    /**
     * @return mixed
     */
    public function getIsMultiple()
    {
        return $this->isMultiple;
    }

    /**
     * @param mixed $isMultiple
     */
    public function setIsMultiple($isMultiple)
    {
        $this->isMultiple = $isMultiple;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }
}