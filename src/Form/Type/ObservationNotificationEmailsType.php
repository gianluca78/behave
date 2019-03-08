<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ObservationNotificationEmailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('observationId', HiddenType::class)
            ->add('notificationEmails', ChoiceType::class, array(
                'label' => 'Emails',
                'mapped' => false,
                'choices' => array(),
                'multiple' => true,
                'attr' => array(
                    'data-role' => 'tagsinput',
                    'class' => 'col-lg-12'
                ),
                /*
                'constraints' => new Assert\Email(array(
                    'message' => 'The email "{{ value }}" is not a valid email.'
                ))*/
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Send'
            ))
        ;

    }
}