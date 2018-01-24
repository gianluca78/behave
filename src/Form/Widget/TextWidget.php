<?php

namespace App\Form\Widget;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TextWidget implements WidgetInterface {

    private $id;
    private $label;
    private $name;
    private $placeholder;
    private $value;

    public function addField(FormBuilderInterface $formBuilderInterface)
    {
        $formBuilderInterface->add(
            $this->label,
            TextType::class,
            array(
                'attr' => array(
                    'id' => $this->id,
                    'name' => $this->name,
                    'placeholder' => $this->placeholder,
                    'value' => $this->value
                ),
                'constraints' => array(
                    new NotBlank(
                        array(
                            'message' => 'This value should not be blank.'
                        )
                    ),
                    new Length(
                        array(
                            'min' => 2,
                            'max' => 255,
                            'minMessage' => 'The inserted text must be at least {{ limit }} characters long',
                            'maxMessage' => 'The inserted text cannot be longer than {{ limit }} characters',
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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