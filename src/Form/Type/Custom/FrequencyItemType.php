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

class FrequencyItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars = array_merge($view->vars, array(
            'observationLengthInMinutes' => str_pad($options['observation_length_in_minutes'], 2, '0', STR_PAD_LEFT) . ':00'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('counter', HiddenType::class, array(
                'data' => $options['counter_value']
            )
        )
            ->add('observationLengthInMinutes', HiddenType::class, array(
                    'data' => $options['observation_length_in_minutes']
                )
            )
            ->add('occurrenceTimestamps', CollectionType::class, array(
                    'allow_add' => true,
                    'entry_type' => HiddenType::class,
                    'entry_options' => array(
                        'constraints' => array(
                            new Regex(
                                array(
                                    'pattern' => '/^[0-9]{10}$/',
                                    'message' => 'The value {{ value }} is not a valid timestamp.'
                                )
                            )
                        )
                    )
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = array(
            'compound' => true,
            'counter_value' => null,
            'observation_length_in_minutes' => null
        );

        $resolver->setDefaults($defaults);
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}