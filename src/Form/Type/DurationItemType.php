<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\DurationItem;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class DurationItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('positionNumber', null, array('required' => true))
            ->add('label', null, array('required' => true))
            ->add('fieldValue', null, array('required' => true))
            ->add('observationLengthInMinutes', null, array('required' => true));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DurationItem::class,
        ));
    }
}