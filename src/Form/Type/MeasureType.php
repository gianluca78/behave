<?php
namespace App\Form\Type;

use App\Entity\Measure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use App\Form\Type\ChoiceItemType;
use App\Form\Type\DirectObservationItemType;
use App\Form\Type\IntegerItemType;
use App\Form\Type\MeterItemType;
use App\Form\Type\RangeItemType;
use App\Form\Type\TextItemType;

class MeasureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'Name of the measure',
                'required' => true,
                'translation_domain' => 'forms',
                'attr' => array(
                    'placeholder' => 'For instance: Anxiety scale',
                )
            ))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'translation_domain' => 'forms',
                'attr' => array(
                    'placeholder' => 'For instance: the scale consists of 10 items designed to assess a person\'s anxiety as presence of cognitive and somatic symptoms'
                )
            ))
            ->add('fileImport', FileType::class, array(
                'required' => false,
                'mapped' => false,
                'translation_domain' => 'forms',
                'label' => 'Import items'
            ))
            ->add('choiceItems', CollectionType::class, array(
                'entry_type' => ChoiceItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'translation_domain' => 'forms'
            ))
            ->add('directObservationItems', CollectionType::class, array(
                'entry_type' => DirectObservationItemType::class,
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
            ->add('submit', SubmitType::class, array(
                'translation_domain' => 'forms'
            ));;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Measure::class,
        ));
    }
}