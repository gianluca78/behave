<?php

namespace App\Form\Builder;

use App\Entity\BehavioralRecordingItem;
use App\Entity\DirectObservationItem;
use App\Entity\IntegerItem;
use App\Entity\MeterItem;
use App\Entity\Observation;
use App\Entity\RangeItem;
use App\Form\Widget\BehavioralRecordingWidget;
use App\Form\Widget\DirectObservationWidget;
use App\Form\Widget\ChoiceWidget;
use App\Form\Widget\IntegerWidget;
use App\Form\Widget\MeterWidget;
use App\Form\Widget\RangeWidget;
use App\Form\Widget\TextWidget;
use App\Entity\ChoiceItem;
use App\Entity\TextItem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
                case 'App\Entity\DirectObservationItem':
                    $this->addDirectObservationWidget($item);
                    break;
                case 'App\Entity\IntegerItem':
                    $this->addIntegerWidget($item);
                    break;
                case 'App\Entity\MeterItem':
                    $this->addMeterWidget($item);
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
                array(
                    'id' => $observation->getId(),
                    'token' => $observation->getToken()
                )
            )
        );
    }

    public function setObservationId($observationId)
    {
        $this->form->add('observationId', HiddenType::class, array(
            'data' => $observationId,
        ));
    }

    public function setUserId($userId)
    {
        $this->form->add('userId', HiddenType::class, array(
            'data' => $userId,
        ));
    }

    private function addChoiceWidget(ChoiceItem $item)
    {
        $choiceWidget = new ChoiceWidget($this->translator);
        $choiceWidget->setLabel($item->getLabel());
        $choiceWidget->setEmptyValue($item->getEmptyValue());
        $choiceWidget->setIsExpanded($item->getIsExpanded());
        $choiceWidget->setIsMultiple($item->getIsMultiple());
        $choiceWidget->setOptions(array_flip(explode(',', $item->getOptions())));

        $this->form = $choiceWidget->addField($this->form, 'item-' . $item->getId());
    }

    private function addDirectObservationWidget(DirectObservationItem $item)
    {
        $directObservationWidget = new DirectObservationWidget($this->translator);
        $directObservationWidget->setLabel($item->getLabel());
        $directObservationWidget->setObservationLengthInMinutes($item->getObservationLengthInMinutes());
        $directObservationWidget->setIntervalLengthInSeconds($item->getIntervalLengthInSeconds());
        $directObservationWidget->setTypology($item->getTypology());

        $this->form = $directObservationWidget->addField($this->form, 'item-' . $item->getId());

    }

    private function addIntegerWidget(IntegerItem $item)
    {
        $integerWidget = new IntegerWidget($this->translator);
        $integerWidget->setLabel($item->getLabel());
        $integerWidget->setValue($item->getFieldValue());

        $this->form = $integerWidget->addField($this->form, 'item-' . $item->getId());
    }

    private function addMeterWidget(MeterItem $item)
    {
        $meterWidget = new MeterWidget($this->translator);
        $meterWidget->setLabel($item->getLabel());
        $meterWidget->setValueX($item->getXValue());
        $meterWidget->setValueY($item->getYValue());
        $meterWidget->setLabel($item->getLabel());
        $meterWidget->setLabelX($item->getLabelX());
        $meterWidget->setLabelMinX($item->getLabelMinX());
        $meterWidget->setLabelMaxX($item->getLabelMaxX());
        $meterWidget->setLabelY($item->getLabelY());
        $meterWidget->setLabelMinY($item->getLabelMinY());
        $meterWidget->setLabelMaxY($item->getLabelMaxY());

        $this->form = $meterWidget->addField($this->form, 'item-' . $item->getId());
    }

    private function addRangeWidget(RangeItem $item)
    {
        $rangeWidget = new RangeWidget($this->translator);
        $rangeWidget->setLabel($item->getLabel());
        $rangeWidget->setMax($item->getMax());
        $rangeWidget->setMin($item->getMin());
        $rangeWidget->setStep($item->getStep());

        $this->form = $rangeWidget->addField($this->form, 'item-' . $item->getId());
    }

    private function addTextWidget(TextItem $item)
    {
        $textWidget = new TextWidget($this->translator);
        $textWidget->setLabel($item->getLabel());
        $textWidget->setPlaceholder($item->getPlaceholder());
        $textWidget->setValue($item->getFieldValue());

        $this->form = $textWidget->addField($this->form, 'item-' . $item->getId());
    }

}