<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Validator\Constraints as Assert;


class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dates', CollectionType::class, array(
                'entry_type' => HiddenType::class,
                'mapped' => false,
                'allow_add' => true
            ))
            ->add('startTime', TimeType::class, array(
                'input'  => 'datetime',
                'minutes' => array('00', '15', '30', '45'),
                'widget' => 'choice',
                'mapped' => false,
                'constraints' => new Assert\Time(
                        array(
                            'message' => 'This value is not a valid time.'
                        )
                    )
            ))
            ->add('endTime', TimeType::class, array(
                'input'  => 'datetime',
                'minutes' => array('00', '15', '30', '45'),
                'widget' => 'choice',
                'mapped' => false,
                'constraints' => new Assert\Time(
                        array(
                            'message' => 'This value is not a valid time.'
                        )
                    )
            ))
            ->add('submit', SubmitType::class)
        ;

    }
}