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

        $choicesForFeedback = array(
            'Bell' => 'bell',
            'Visual feedback' => 'visual-feedback'
        );

        $builder
            ->add('positionNumber', HiddenType::class, array('required' => true))
            ->add('label', null, array('required' => true))
            ->add('observationLengthInMinutes', null, array('required' => true))
            ->add('intervalLengthInSeconds', null, array(
                'required' => false,
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
            ->add('feedbackForIntervalRecording', ChoiceType::class, array(
                'choices' => $choicesForFeedback,
                'constraints' => array(
                    new Choice(array(
                        'choices' => $choicesForFeedback,
                        'message' => 'Choose a valid feedback typology'
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
