<?php

namespace App\Form\Builder;

use App\Entity\Observation;
use App\Entity\RangeItem;
use App\Form\Widget\ChoiceWidget;
use App\Form\Widget\FrequencyWidget;
use App\Form\Widget\RangeWidget;
use App\Form\Widget\TextWidget;
use App\Entity\ChoiceItem;
use App\Entity\FrequencyItem;
use App\Entity\TextItem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class FormBuilder {

    private $form;
    private $formFactory;
    private $urlGenerator;

    public function __construct(FormFactoryInterface $formFactoryInterface,
                                UrlGeneratorInterface $urlGeneratorInterface)
    {
        $this->urlGenerator = $urlGeneratorInterface;
        $this->formFactory = $formFactoryInterface;
        $this->form = $this->formFactory->createBuilder()
                    ->setMethod('POST')
        ;
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
                case 'App\Entity\TextItem':
                    $this->addTextWidget($item);
                    break;
                case 'App\Entity\FrequencyItem':
                    $this->addFrequencyWidget($item);
                    break;
                case 'App\Entity\RangeItem':
                    $this->addRangeWidget($item);
                    break;
                case 'App\Entity\ChoiceItem':
                    $this->addChoiceWidget($item);
                    break;
            }
        }

        $this->form->add('submit', SubmitType::class);
    }

    public function setAction(Observation $observation)
    {
        $this->form->setAction(
            $this->urlGenerator->generate(
                'observation_measure',
                array('id' => $observation->getId())
            )
        );
    }

    private function addChoiceWidget(ChoiceItem $item)
    {
        //var_dump(explode(PHP_EOL, $item->getOptions())); exit;

        $choiceWidget = new ChoiceWidget();
        $choiceWidget->setLabel($item->getLabel());
        $choiceWidget->setEmptyValue($item->getEmptyValue());
        $choiceWidget->setIsExpanded($item->getIsExpanded());
        $choiceWidget->setIsMultiple($item->getIsMultiple());
        $choiceWidget->setOptions(array_flip(explode(PHP_EOL, $item->getOptions())));

        $this->form = $choiceWidget->addField($this->form);
    }

    private function addTextWidget(TextItem $item)
    {
        $textWidget = new TextWidget();
        $textWidget->setLabel($item->getLabel());
        $textWidget->setPlaceholder($item->getPlaceholder());
        $textWidget->setValue($item->getFieldValue());

        $this->form = $textWidget->addField($this->form);
    }

    private function addFrequencyWidget(FrequencyItem $item)
    {
        $frequencyWidget = new FrequencyWidget();
        $frequencyWidget->setLabel($item->getLabel());
        $frequencyWidget->setValue($item->getFieldValue());
        $frequencyWidget->setObservationLengthInMinutes($item->getObservationLengthInMinutes());

        $this->form = $frequencyWidget->addField($this->form);

    }

    private function addRangeWidget(RangeItem $item)
    {
        $rangeWidget = new RangeWidget();
        $rangeWidget->setLabel($item->getLabel());
        $rangeWidget->setMax($item->getMax());
        $rangeWidget->setMin($item->getMin());
        $rangeWidget->setStep($item->getStep());

        $this->form = $rangeWidget->addField($this->form);
    }
}