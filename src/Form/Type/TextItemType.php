<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\TextItem;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class TextItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fieldValue', null, array('required' => true))
            ->add('placeholder', null, array('required' => true))
            ->add('positionNumber', null, array('required' => true))
            ->add('label', null, array('required' => true));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TextItem::class,
        ));
    }
}