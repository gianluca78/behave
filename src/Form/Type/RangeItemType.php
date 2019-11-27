<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\RangeItem;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class RangeItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('positionNumber', HiddenType::class, array('required' => true))
            ->add('label', null, array(
                'required' => true,
                'label' => 'Text of the item',
                'attr' => array(
                    'placeholder' => 'For instance, how many times he calls out in class?'
                )

            ))
            ->add('min', null, array(
                'required' => true,
                'label' => 'Minimum value',
                'attr' => array(
                    'placeholder' => 'For instance, 0'
                )

            ))
            ->add('max', null, array(
                'required' => true,
                'label' => 'Maximum value',
                'attr' => array(
                    'placeholder' => 'For instance, 10'
                )
            ))
            ->add('step', null, array(
                'required' => true,
                'attr' => array(
                    'value' => 1,
                    'placeholder' => 'The value that specifies the increment from the minimum value to the maximum value.'

                )
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => RangeItem::class,
        ));
    }
}