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

use App\Form\Type\ObservationSchedulerType;

use Doctrine\ORM\EntityRepository;

class ObservationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('base_observation', BaseObservationType::class, array(
            'data_class' => Observation::class,
            'creatorUserId' => $options['creatorUserId'],
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Observation::class,
            'creatorUserId' => array()
        ));
    }
}