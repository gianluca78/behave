<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Student;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('studentId', null, array(
                'required' => true,
                'label' => 'Student id',
                'translation_domain' => 'forms',
                'attr' => array(
                    'placeholder' => 'A nickname or a code to identify the student'
                )
            ))
            ->add('yearOfBirth', ChoiceType::class, array(
                'choices' => array_combine(range(date('Y'), 1970), range(date('Y'), 1970)),
                'placeholder' => '-- Select the year of birth --',
                'translation_domain' => 'forms'
            ))
            ->add('sex', ChoiceType::class, array(
                'label' => 'Sex',
                'choices' => array(
                    'Male' => 0,
                    'Female' => 1
                ),
                'translation_domain' => 'forms'
            ))
            ->add('countryType', CountryType::class, array(
                'label' => 'Country',
                'placeholder' => '-- Select the country --',
                'translation_domain' => 'forms'
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Submit',
                'translation_domain' => 'forms'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Student::class,
        ));
    }
}