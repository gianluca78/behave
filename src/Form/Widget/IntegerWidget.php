<?php

namespace App\Form\Widget;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Translation\TranslatorInterface;

class IntegerWidget implements WidgetInterface {

    private $label;
    private $translator;
    private $value;

    CONST NOT_BLANK_MESSAGE = 'This value should not be blank.';
    CONST MESSAGE = 'The value {{ value }} is not a valid {{ type }}.';
    CONST ITEM_TYPOLOGY = 'integer';

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function addField(FormBuilderInterface $formBuilderInterface, $name)
    {
        $formBuilderInterface->add(
            $name,
            IntegerType::class,
            array(
                'attr' => array(
                    'value' => $this->value
                ),
                'constraints' => array(
                    new NotBlank(
                        array(
                            'message' => self::NOT_BLANK_MESSAGE
                        )
                    ),
                    new Type(
                        array(
                            'type' => 'integer',
                            'message' => self::MESSAGE,
                        )
                    )
                ),
                'label' => $this->label,
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