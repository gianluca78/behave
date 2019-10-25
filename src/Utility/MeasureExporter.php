<?php
namespace App\Utility;

use App\Entity\Measure;

class MeasureExporter {

    public function export(Measure $measure) {
        $export['name'] = $measure->getName();
        $export['description'] = $measure->getDescription();

        $export['choiceItems'] = array();
        $export['integerItems'] = array();
        $export['meterItems'] = array();
        $export['rangeItems'] = array();
        $export['textItems'] = array();
        $export['directObservationItems'] = array();

        foreach($measure->getChoiceItems() as $item) {
            $export['choiceItems'][] = $item;
        }

        foreach($measure->getIntegerItems() as $item) {
            $export['integerItems'][] = $item;
        }

        foreach($measure->getMeterItems() as $item) {
            $export['meterItems'][] = $item;
        }

        foreach($measure->getRangeItems() as $item) {
            $export['rangeItems'][] = $item;
        }

        foreach($measure->getTextItems() as $item) {
            $export['textItems'][] = $item;
        }

        foreach($measure->getDirectObservationItems() as $item) {
            $export['directObservationItems'][] = $item;
        }

        return serialize($export);
    }
} 