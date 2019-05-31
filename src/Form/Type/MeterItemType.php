<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\MeterItem;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class MeterItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('positionNumber', HiddenType::class, array('required' => true))
            ->add('label', null, array(
                'required' => true,
                'label' => 'Name of the diagram',
                'attr' => array(
                    'placeholder' => 'For example: Mood meter'
                )
            ))
            ->add('xValue', HiddenType::class, array('required' => true))
            ->add('yValue', HiddenType::class, array('required' => true))
            ->add('labelY', null, array(
                'required' => true,
                'label' => 'Y axis name',
                'attr' => array(
                    'placeholder' => 'For example: Pleasantness'
                )
            ))
            ->add('labelMaxY', null, array(
                'required' => true,
                'label' => 'Label of the max value for the y axis',
                'attr' => array(
                    'placeholder' => 'For example: High'
                )
            ))
            ->add('labelMinY', null, array(
                'required' => true,
                'label' => 'Label of the min value for the y axis',
                'attr' => array(
                    'placeholder' => 'For example: Low'
                )
            ))
            ->add('labelX', null, array(
                'required' => true,
                'label' => 'X axis name',
                'attr' => array(
                    'placeholder' => 'For example: Energy'
                )
            ))
            ->add('labelMaxX', null, array(
                'required' => true,
                'label' => 'Label of the max value for the x axis',
                'attr' => array(
                    'placeholder' => 'For example: High'
                )
            ))
            ->add('labelMinX', null, array(
                'required' => true,
                'label' => 'Label of the min value for the x axis',
                'attr' => array(
                    'placeholder' => 'For example: Low'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MeterItem::class,
        ));
    }
}