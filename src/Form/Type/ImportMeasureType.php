<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImportMeasureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array(
                'label' => 'File',
                'required' => true,
                'translation_domain' => 'forms',
                'constraints' => array(
                    new Assert\File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'text/plain',
                            'application/octet-stream'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid measure file',
                    ])
                ),
                'mapped' => false
                )
            )
            ->add('submit', SubmitType::class, array(
                'translation_domain' => 'forms'
            ));
    }
}