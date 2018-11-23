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

class BaseObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isSingleCaseDesign', null, array(
                    'required' => false,
                    'value' => 0,
                    'label' => 'Single case'
                )
            )
            ->add('name', null, array('required' => true))
            ->add('description', TextareaType::class, array('required' => true))
            ->add('fillingInstructions', TextareaType::class, array('required' => false))
            ->add('place', TextareaType::class, array('required' => false))
            ->add('setting', TextareaType::class, array('required' => false))
            ->add('observerUsername', TextType::class, array(
                'required' => true,
                'label' => 'Observer username, email, or surname and name'
                )
            )
            ->add('observerUserId', HiddenType::class, array('required' => true))
            ->add('measure', EntityType::class, array(
                    'attr' => array(
                        'data-live-search' => 'true',
                        'title' => 'Choose one of the following...',
                        'data-size' => 5
                    ),
                    'class' => Measure::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($options) {
                            return $er->createQueryBuilder('m')
                                ->where('m.creatorUserId = :creatorUserId')
                                ->setParameter('creatorUserId', $options['creatorUserId'])
                                ->orderBy('m.name', 'asc')
                                ;
                        },
                )
            )
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
            'creatorUserId' => array()
        ));
    }
}