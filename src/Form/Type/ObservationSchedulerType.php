<?php
namespace App\Form\Type;

use App\Entity\ObservationScheduler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\CallbackTransformer;

class ObservationSchedulerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('startDate', TextType::class, array(
            'required' => false
        ))
            ->add('timeOption', ChoiceType::class, array(
                'required' => false,
                'placeholder' => false,
                'choices' => array(
                    'All day' => 0,
                    'Time range' => 1,
                    'Exact time' => 2
                )
            ))
            ->add('timeRangeStartTime', TimeType::class, array(
                'input'  => 'datetime',
                'minutes' => array('00', '30'),
                'widget' => 'choice',
                'constraints' => array(
                    new Assert\Time()
                )
            ))
            ->add('timeRangeEndTime', TimeType::class, array(
                'input'  => 'datetime',
                'minutes' => array('00', '30'),
                'widget' => 'choice',
                'constraints' => new Assert\Time()
            ))
            ->add('exactTime', TimeType::class, array(
                'input'  => 'datetime',
                'minutes' => array('00', '30'),
                'widget' => 'choice',
                'constraints' => new Assert\Time()
            ))
            ->add('repeatOption', ChoiceType::class, array(
                'required' => false,
                'placeholder' => false,
                'choices' => array(
                    'None' => 0,
                    'Weekly' => 1
                )
            ))
            ->add('weeklyNumberOfWeeks', IntegerType::class, array(
                'required' => false,
                'label' => 'every n week(s)'
            ))
            ->add('weeklyDaysOfWeek', ChoiceType::class, array(
                'required' => false,
                'placeholder' => false,
                'choices' => array(
                    'Sun' => 0,
                    'Mon' => 1,
                    'Tue' => 2,
                    'Wed' => 3,
                    'Thu' => 4,
                    'Fri' => 5,
                    'Sat' => 6
                ),
                'expanded' => true,
                'multiple' => true
            ))
            ->add('repeatEndOption', ChoiceType::class, array(
                    'required' => false,
                    'placeholder' => false,
                    'choices' => array(
                        'After' => 0,
                        'On date' => 1
                    )
                )
            )
            ->add('repeatEndAfterNumberOfOccurrences', IntegerType::class, array(
                    'required' => false,
                    'label' => 'Nr. occurrences'
                )
            )
            ->add('repeatEndDate', TextType::class, array(
                    'required' => false,
                    'constraints' => new Assert\Date()
                )
            )
        ;

        $builder->get('startDate')
            ->addModelTransformer(new CallbackTransformer(
                function ($dateTimeToString) {
                    return $dateTimeToString->format('Y-m-d');
                },
                function ($stringToDatetime) {
                    return \DateTime::createFromFormat('Y-m-d', $stringToDatetime);
                }
            ));

        $builder->get('repeatEndDate')
            ->addModelTransformer(new CallbackTransformer(
                function ($dateTimeToString) {
                    return $dateTimeToString->format('Y-m-d');
                },
                function ($stringToDatetime) {
                    return \DateTime::createFromFormat('Y-m-d', $stringToDatetime);
                }
            ))
        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ObservationScheduler::class,
        ));
    }
}