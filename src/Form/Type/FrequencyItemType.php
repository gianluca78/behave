<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\FrequencyItem;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class FrequencyItemType extends AbstractType
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
            'data_class' => FrequencyItem::class,
        ));
    }
}