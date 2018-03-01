<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Observation;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use App\Form\Type\ChoiceItemType;
use App\Form\Type\DurationItemType;
use App\Form\Type\FrequencyItemType;
use App\Form\Type\IntegerItemType;
use App\Form\Type\MeterItemType;
use App\Form\Type\RangeItemType;
use App\Form\Type\TextItemType;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isEnabled', null, array('required' => false))
            ->add('name', null, array('required' => true))
            ->add('description', null, array('required' => true))
            ->add('choiceItems', CollectionType::class, array(
                'entry_type' => ChoiceItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('durationItems', CollectionType::class, array(
                'entry_type' => DurationItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('frequencyItems', CollectionType::class, array(
                'entry_type' => FrequencyItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('integerItems', CollectionType::class, array(
                'entry_type' => IntegerItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('meterItems', CollectionType::class, array(
                'entry_type' => MeterItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('rangeItems', CollectionType::class, array(
                'entry_type' => RangeItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('textItems', CollectionType::class, array(
                'entry_type' => TextItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('submit', SubmitType::class);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Observation::class,
        ));
    }
}