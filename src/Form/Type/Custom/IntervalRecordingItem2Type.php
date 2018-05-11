<?php
namespace App\Form\Type\Custom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Regex;

class IntervalRecordingItem2Type extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('intervalNumber', HiddenType::class)
            ->add('isBehaviorOccurred', HiddenType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = array(
            'compound' => true
        );

        $resolver->setDefaults($defaults);
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}