<?php
namespace App\Form\Type\Custom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Regex;

class DirectObservationItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars = array_merge($view->vars, array(
            'observationLengthInMinutes' => str_pad($options['observation_length_in_minutes'], 2, '0', STR_PAD_LEFT) . ':00',
            'typology' => $options['typology'],
            'counter' => $options['counter_value'],
            'label' => $options['label']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('observationLengthInMinutes', HiddenType::class, array(
                    'data' => $options['observation_length_in_minutes']
                )
            )
            ->add('intervalLengthInSeconds', HiddenType::class, array(
                    'data' => $options['interval_length_in_seconds']
                )
            )
            ->add('feedbackForIntervalRecording', HiddenType::class, array(
                    'data' => $options['feedback_for_interval_recording']
                )
            )
            ->add('typology', HiddenType::class, array(
                    'data' => $options['typology']
                )
            )
            ->add('occurrenceTimestamps', CollectionType::class, array(
                    'allow_add' => true,
                    'entry_type' => HiddenType::class,
                    'entry_options' => array(
                        'constraints' => array(
                            new Regex(
                                array(
                                    'pattern' => '/^[0-9]{10}$/',
                                    'message' => 'The value {{ value }} is not a valid timestamp.'
                                )
                            )
                        )
                    )
                )
            )
            ->add('counter', HiddenType::class, array(
                'data' => $options['counter_value']
            ))
            ->add('intervalData', CollectionType::class, array(
                    'allow_add' => true,
                    'entry_type' => IntervalRecordingItemType::class,
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = array(
            'compound' => true,
            'counter_value' => -1,
            'interval_length_in_seconds' => null,
            'feedback_for_interval_recording' => null,
            'label' => '',
            'observation_length_in_minutes' => null,
            'typology' => null,
        );

        $resolver->setDefaults($defaults);
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}