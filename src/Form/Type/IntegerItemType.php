<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\IntegerItem;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class IntegerItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('positionNumber', HiddenType::class, array('required' => true))
            ->add('label', null, array('required' => true))
            ->add('fieldValue', HiddenType::class, array('required' => true));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => IntegerItem::class,
        ));
    }
}