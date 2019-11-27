<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ChoiceItem;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ChoiceItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('positionNumber', HiddenType::class, array('required' => true))
            ->add('choiceType', ChoiceType::class, array(
                'choices' => array(
                    'Checkboxes' => 0,
                    'Dropdown list' => 1,
                    'Dropdown list with multiple selection' => 2,
                    'Radio buttons' => 3,
                ),
                'placeholder' => '-- Select a type --'
            ))
            ->add('isExpanded', HiddenType::class, array('required' => false))
            ->add('isMultiple', HiddenType::class, array('required' => false))
            ->add('label', null, array(
                'required' => true,
                'label' => 'Text of the item',
                'attr' => array(
                    'placeholder' => 'For example: What is your favourite color?'
                )
            ))
            ->add('emptyValue', null, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Default value displayed is selected until the user clicks on it.'
                    )
                )
            )
            ->add('options', TextType::class, array(
                'required' => true,
                'attr' => array(
                    'data-role' => 'tagsinput',
                    'placeholder' => 'Write the options and press enter after entering each individual one.'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ChoiceItem::class,
        ));
    }
}