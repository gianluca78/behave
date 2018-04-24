<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class IntervalRecordingItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intervalNumber', HiddenType::class, array('required' => true))
            ->add('isBehaviorOccurred', HiddenType::class, array('required' => true));
    }
}