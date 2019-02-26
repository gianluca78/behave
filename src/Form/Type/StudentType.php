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
            ->add('studentId', null, array('required' => true))
            ->add('yearOfBirth', ChoiceType::class, array(
                'choices' => array_combine(range(date('Y'), 1970), range(date('Y'), 1970)),
                'placeholder' => '-- Select the year of birth --'
            ))
            ->add('sex', ChoiceType::class, array(
                'choices' => array(
                    'Male' => 0,
                    'Female' => 1
                )
            ))
            ->add('countryType', CountryType::class, array(
                'label' => 'Country',
                'placeholder' => '-- Select the country --'
            ))
            ->add('submit', SubmitType::class);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Student::class,
        ));
    }
}