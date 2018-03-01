<?php
namespace App\Form\Type\Custom;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class MeterItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars = array_merge($view->vars, array(
            'labelY' => $options['label_y'],
            'labelMaxY' => $options['label_max_y'],
            'labelMinY' => $options['label_min_y'],
            'labelX' => $options['label_x'],
            'labelMaxX' => $options['label_max_x'],
            'labelMinX' => $options['label_min_x'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('xValue', HiddenType::class);
        $builder->add('yValue', HiddenType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = array(
            'compound' => true,
            'label_y' => null,
            'label_max_y' => null,
            'label_min_y' => null,
            'label_x' => null,
            'label_max_x' => null,
            'label_min_x' => null,
        );

        $resolver->setDefaults($defaults);
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}