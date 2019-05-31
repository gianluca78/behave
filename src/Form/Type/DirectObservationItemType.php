<?php
namespace App\Form\Type;

use App\Entity\DirectObservationItem;
use App\Validator\Constraints\IsMultiple;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Choice;

class DirectObservationItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            'Duration Recording' => 'duration',
            'Frequency' => 'frequency',
            'Interval Recording - Whole Interval' => 'whole-interval',
            'Interval Recording - Partial Interval' => 'partial-interval',
            'Interval Recording - Momentary Time Sampling' => 'momentary-time-sampling'
        );

        $builder
            ->add('positionNumber', HiddenType::class, array('required' => true))
            ->add('label', null, array(
                'label' => 'Text of the item',
                'required' => true,
                'attr' => array(
                        'placeholder' => 'For instance: Interruptions of conversation'
                    )
                )
            )
            ->add('observationLengthInMinutes', null, array(
                'required' => true,
                'attr' => array(
                    'placeholder' => 'In general, 10 or 15 minutes'
                    )
                )
            )
            ->add('intervalLengthInSeconds', null, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'The number of seconds has to be multiple of the observation length'
                ),
                'constraints' => array(
                    new IsMultiple()
                )
            ))
            ->add('typology', ChoiceType::class, array(
                'choices' => $choices,
                'constraints' => array(
                    new Choice(array(
                        'choices' => $choices,
                        'message' => 'Choose a valid typology of observation'
                    ))
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DirectObservationItem::class,
        ]);
    }
}
