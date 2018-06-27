<?php
namespace App\Form\Type;

use App\Entity\Measure;
use App\Entity\Observation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isEnabled', null, array('required' => false))
            ->add('name', null, array('required' => true))
            ->add('description', TextareaType::class, array('required' => true))
            ->add('observerUsername', TextType::class, array('required' => true))
            ->add('observerUserId', HiddenType::class, array('required' => true))
            ->add('measure', EntityType::class, array(
                    'class' => Measure::class,
                    'choice_label' => 'name',
                    'placeholder' => '-- select --',
                    'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('m')
                                ->where('m.isShared = :isShared')
                                ->setParameter('isShared', 1)
                                ;
                        },
                )
            )
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Observation::class
        ));
    }
}