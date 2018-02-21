<?php

namespace App\Form\Builder;

use App\Entity\DurationItem;
use App\Entity\IntegerItem;
use App\Entity\Observation;
use App\Entity\RangeItem;
use App\Form\Widget\ChoiceWidget;
use App\Form\Widget\DurationWidget;
use App\Form\Widget\FrequencyWidget;
use App\Form\Widget\IntegerWidget;
use App\Form\Widget\RangeWidget;
use App\Form\Widget\TextWidget;
use App\Entity\ChoiceItem;
use App\Entity\FrequencyItem;
use App\Entity\TextItem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FormBuilder {

    private $form;
    private $formFactory;
    private $translator;
    private $urlGenerator;

    public function __construct(FormFactoryInterface $formFactoryInterface,
                                UrlGeneratorInterface $urlGeneratorInterface,
                                TranslatorInterface $translator)
    {
        $this->urlGenerator = $urlGeneratorInterface;
        $this->formFactory = $formFactoryInterface;
        $this->form = $this->formFactory->createBuilder()
                    ->setMethod('POST')
        ;
        $this->translator = $translator;
    }

    /**
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    public function addItems($items)
    {
        foreach($items as $key => $item) {
            switch(get_class($item)) {
                case 'App\Entity\ChoiceItem':
                    $this->addChoiceWidget($item);
                    break;
                case 'App\Entity\DurationItem':
                    $this->addDurationWidget($item);
                    break;
                case 'App\Entity\FrequencyItem':
                    $this->addFrequencyWidget($item);
                    break;
                case 'App\Entity\IntegerItem':
                    $this->addIntegerWidget($item);
                    break;
                case 'App\Entity\RangeItem':
                    $this->addRangeWidget($item);
                    break;
                case 'App\Entity\TextItem':
                    $this->addTextWidget($item);
                    break;
            }
        }

        $this->form->add('submit', SubmitType::class);
    }

    public function setAction(Observation $observation)
    {
        $this->form->setAction(
            $this->urlGenerator->generate(
                'measure',
                array('id' => $observation->getId())
            )
        );
    }

    private function addChoiceWidget(ChoiceItem $item)
    {
        $choiceWidget = new ChoiceWidget($this->translator);
        $choiceWidget->setLabel($item->getLabel());
        $choiceWidget->setEmptyValue($item->getEmptyValue());
        $choiceWidget->setIsExpanded($item->getIsExpanded());
        $choiceWidget->setIsMultiple($item->getIsMultiple());
        $choiceWidget->setOptions(array_flip(explode(PHP_EOL, $item->getOptions())));

        $this->form = $choiceWidget->addField($this->form);
    }

    private function addDurationWidget(DurationItem $item)
    {
        $durationWidget = new DurationWidget($this->translator);
        $durationWidget->setLabel($item->getLabel());
        $durationWidget->setValue($item->getFieldValue());
        $durationWidget->setObservationLengthInMinutes($item->getObservationLengthInMinutes());

        $this->form = $durationWidget->addField($this->form);

    }

    private function addFrequencyWidget(FrequencyItem $item)
    {
        $frequencyWidget = new FrequencyWidget($this->translator);
        $frequencyWidget->setLabel($item->getLabel());
        $frequencyWidget->setValue($item->getFieldValue());
        $frequencyWidget->setObservationLengthInMinutes($item->getObservationLengthInMinutes());

        $this->form = $frequencyWidget->addField($this->form);

    }

    private function addIntegerWidget(IntegerItem $item)
    {
        $integerWidget = new IntegerWidget($this->translator);
        $integerWidget->setLabel($item->getLabel());
        $integerWidget->setValue($item->getFieldValue());

        $this->form = $integerWidget->addField($this->form);
    }

    private function addRangeWidget(RangeItem $item)
    {
        $rangeWidget = new RangeWidget($this->translator);
        $rangeWidget->setLabel($item->getLabel());
        $rangeWidget->setMax($item->getMax());
        $rangeWidget->setMin($item->getMin());
        $rangeWidget->setStep($item->getStep());

        $this->form = $rangeWidget->addField($this->form);
    }

    private function addTextWidget(TextItem $item)
    {
        $textWidget = new TextWidget($this->translator);
        $textWidget->setLabel($item->getLabel());
        $textWidget->setPlaceholder($item->getPlaceholder());
        $textWidget->setValue($item->getFieldValue());

        $this->form = $textWidget->addField($this->form);
    }

}