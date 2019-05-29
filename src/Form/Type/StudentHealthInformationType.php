<?php

namespace App\Form\Type;

use App\Entity\Core\Dsm5Disorder;
use App\Entity\StudentHealthInformation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class StudentHealthInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ageOfOnset')
            ->add('isSecondaryToAnotherMedicalCondition', null, array(
                'label' => ' '
            ))
            ->add('medicalCondition', TextareaType::class, array(
                'required' => false
            ))
            ->add('dsm5Disorder')
            ->add('dsm5DisorderLabel', TextType::class, array(
                'mapped' => false,
                'label' => 'Disorder',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Search by disorder name, DSM5, ICD9, OR ICD10 codes'
                )
            ))
            ->add('comorbidDsm5Disorders', EntityType::class, [
                'label' => 'Comorbid disorders',
                'class' => Dsm5Disorder::class,
                'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('d')
                            ->orderBy('d.description', 'ASC');
                    },
                'choice_label' => 'description',
                'multiple' => true,
                'required' => false
            ])
            ->add('submit', SubmitType::class);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StudentHealthInformation::class,
        ]);
    }
}
