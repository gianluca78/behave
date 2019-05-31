<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\TextItem;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class TextItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('positionNumber', HiddenType::class, array('required' => true))
            ->add('label', null, array(
                'required' => true,
                'label' => 'Text of the item',
                'attr' => array(
                    'placeholder' => 'For instance: What happens next, or as a result of the child\'s behavior?'
                )
            ))
            ->add('fieldValue', HiddenType::class, array('required' => true))
            ->add('placeholder', null, array(
                'required' => true,
                'attr' => array(
                    'placeholder' => 'This is a short hint that describes the expected value of an input field'
                )

            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TextItem::class,
        ));
    }
}