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
            ->add('label', null, array('required' => true))
            ->add('xValue', HiddenType::class, array('required' => true))
            ->add('yValue', HiddenType::class, array('required' => true))
            ->add('labelY', null, array('required' => true))
            ->add('labelMaxY', null, array('required' => true))
            ->add('labelMinY', null, array('required' => true))
            ->add('labelX', null, array('required' => true))
            ->add('labelMaxX', null, array('required' => true))
            ->add('labelMinX', null, array('required' => true));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MeterItem::class,
        ));
    }
}