<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\ObservationPhase;

class ObservationPhaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
                    'required' => true
                ))
                ->add('isIntervention', null, array(
                'label' => ' '
                ))
                ->add('isUnderPharmacologicalTreatment', null, array(
                'label' => ' '
                ))
                ->add('intervention', TextareaType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Please describe the behavioral intervention'
                )
            ))
                ->add('submit', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ObservationPhase::class,
        ));
    }
}