<?php
namespace App\Form\Type;

use App\Entity\Measure;
use App\Entity\Observation;
use App\Entity\Student;

use App\Security\Encoder\OpenSslEncoder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ObservationType extends AbstractType
{
    private $sslEncoder;

    public function __construct(OpenSslEncoder $sslEncoder) {
        $this->sslEncoder = $sslEncoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isEnabled', null, array('required' => false))
            ->add('name', null, array('required' => true))
            ->add('description', TextareaType::class, array('required' => true))
            ->add('observerUsername', null, array('required' => true))
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
            ->add('student', EntityType::class, array(
                'class' => Student::class,
                'choice_label' => 'studentId',
                'placeholder' => '-- select --',
                'query_builder' => function (EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('s')
                            ->where('s.creatorUsername = :creatorUsername')
                            ->setParameter('creatorUsername', $this->sslEncoder->encrypt($options['creator_username']))
                            ->orderBy('s.creatorUsername', 'ASC');
                    },
            ))
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Observation::class,
            'creator_username' => null
        ));
    }
}