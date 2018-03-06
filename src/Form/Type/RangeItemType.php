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
            ->add('label', null, array('required' => true))
            ->add('min', null, array('required' => true))
            ->add('max', null, array('required' => true))
            ->add('step', null, array('required' => true, 'attr' => array('value' => 1)));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => RangeItem::class,
        ));
    }
}