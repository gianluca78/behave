<?php
namespace App\Utility;


class CouchDbDataTransformer {

    /**
     * Return an array of items and values grouped for data
     *
     * @param $data
     * @param $replaceItemNumbers
     * @return array
     */
    public function transformByData($data, $replaceItemNumbers = true)
    {
        $results = array();

        foreach($data as $values) {

            $itemNumber = 0;

            $row = array();

            foreach($values['value'] as $key => $value) {
                if(strpos($key, 'item') !== false && strpos($key, 'typology') == false && strpos($key, 'label') == false) {

                    $itemNumber = ($replaceItemNumbers) ? $itemNumber += 1 : $key;
                    $itemNumberIndex = (is_string($itemNumber)) ? $itemNumber : 'item-' . $itemNumber;

                    $row[$itemNumberIndex]['value'] = $value;
                    $row[$itemNumberIndex]['label'] = $values['value'][$key . '-label'];
                    $row[$itemNumberIndex]['typology'] = $values['value'][$key . '-typology'];

                    if($values['value'][$key . '-typology'] == 'direct-observation') {
                        $row[$itemNumberIndex]['observationData'] = $this->calculateDirectObservationData($value);
                    }

                }

                if($replaceItemNumbers) {
                    $row['createdAt'] = $values['value']['createdAt']['date'];
                }

            }

            $results[] = $row;

        }

        return $results;
    }

    /**
     * Return an array of items and values grouped for item typologies
     *
     * @param $data
     * @return array
     */
    public function transformByItemTypology($data)
    {
        $results = array();

        foreach($data as $values) {
            $itemNumber = 0;

            foreach($values['value'] as $key => $value) {
                if(strpos($key, 'item') !== false && strpos($key, 'typology') == false && strpos($key, 'label') == false) {

                    $itemNumber += 1;

                    switch($values['value'][$key . '-typology']) {
                        case 'choice-checkboxes':
                            break;

                        case 'choice-dropdown':
                        case 'choice-radio':
                            if(is_int($value)) {
                                $results['item-' . $itemNumber][] = (float) $value;
                            }
                            break;
                        case 'direct-observation':
                            $results['item-' . $itemNumber][] = (float) $this->calculateDirectObservationData($value);
                            break;

                        case 'integer':
                        case 'range':
                            $results['item-' . $itemNumber][] = (float) $value;
                            break;

                        case 'meter':
                            $results['item-' . $itemNumber . '-' . $values['value'][$key . '-label-x']][] = (float) $value['xValue'];
                            $results['item-' . $itemNumber . '-' . $values['value'][$key . '-label-y']][] = (float) $value['yValue'];
                            break;

                        case 'text':
                            break;

                    }
                }
            }


        }

        return $results;

    }

    public function transformByNameAndData($data)
    {
        $results = array();

        $itemTypologyData = $this->transformByItemTypology($data);

        foreach($itemTypologyData as $name => $values) {
            $results[] = array(
                'name' => $name,
                'data' => $values
            );
        }

        return $results;
    }

    public function calculateDirectObservationData($value)
    {
        //patch for the typology issue
        //when the widget is duration and the user starts an observation without clicking on stop
        if(!$value['counter'] && !$value['intervalData'] && count($value['occurrenceTimestamps']) % 2 != 0) {
            array_pop($value['occurrenceTimestamps']);
        }

        if(!$value['typology'] && !$value['counter']) {
            $numberOfSeconds = 0;

            for($i=0; $i<count($value['occurrenceTimestamps']); $i+=2) {
                $numberOfSeconds += $value['occurrenceTimestamps'][$i + 1] - $value['occurrenceTimestamps'][$i];
            }

            return $numberOfSeconds;

        }

        return $value['counter'];
    }
}