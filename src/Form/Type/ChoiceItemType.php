<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ChoiceItem;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ChoiceItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                                                                        ->add('positionNumber', null, array('required' => true))
                                                                ->add('label', null, array('required' => true))
                                                                ->add('emptyValue', null, array('required' => false))
                                                                ->add('isExpanded', null, array('required' => false))
                                                                ->add('isMultiple', null, array('required' => false))
                                                                ->add('options', null, array('required' => true))
                                    
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ChoiceItem::class,
        ));
    }
}