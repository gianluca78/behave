<?php

namespace App\Form\Widget;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class TextWidget implements WidgetInterface {

    private $label;
    private $placeholder;
    private $translator;
    private $value;

    CONST NOT_BLANK_MESSAGE = 'This value should not be blank.';
    CONST LENGTH_MIN = 2;
    CONST LENGTH_MAX = 255;
    CONST LENGTH_MIN_MESSAGE = 'The inserted text must be at least {{ limit }} characters long';
    CONST LENGTH_MAX_MESSAGE = 'The inserted text cannot be longer than {{ limit }} characters';
    CONST ITEM_TYPOLOGY = 'text';

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function addField(FormBuilderInterface $formBuilderInterface, $name)
    {
        $formBuilderInterface->add(
            $name,
            TextType::class,
            array(
                'attr' => array(
                    'placeholder' => $this->placeholder,
                    'value' => $this->value
                ),
                'constraints' => array(
                    new NotBlank(
                        array(
                            'message' => self::NOT_BLANK_MESSAGE
                        )
                    ),
                    new Length(
                        array(
                            'min' => self::LENGTH_MIN,
                            'max' => self::LENGTH_MAX,
                            'minMessage' => self::LENGTH_MIN_MESSAGE,
                            'maxMessage' => self::LENGTH_MAX_MESSAGE,
                        )
                    )
                ),
                'label' => $this->label
            )
        );

        $formBuilderInterface->add(
            $name . '-typology',
            HiddenType::class,
            array(
                'attr' => array(
                    'value' => self::ITEM_TYPOLOGY
                )
            ));

        $formBuilderInterface->add(
            $name . '-label',
            HiddenType::class,
            array(
                'attr' => array(
                    'value' => $this->label
                )
            ));

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