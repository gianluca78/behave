<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ChoiceItem;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ChoiceItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('positionNumber', HiddenType::class, array('required' => true))
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Checkboxes' => 0,
                    'Dropdown list' => 1,
                    'Dropdown list with multiple selection' => 2,
                    'Radio buttons' => 3,
                ),
                'mapped' => false,
                'placeholder' => '-- Select a type --'
            ))
            ->add('isExpanded', HiddenType::class, array('required' => false))
            ->add('isMultiple', HiddenType::class, array('required' => false))
            ->add('label', null, array('required' => true))
            ->add('emptyValue', null, array('required' => false))
            ->add('options', TextareaType::class, array('required' => true));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ChoiceItem::class,
        ));
    }
}