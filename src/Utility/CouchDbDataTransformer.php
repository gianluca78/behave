<?php
namespace App\Utility;


class CouchDbDataTransformer {

    /**
     * Return an array of items and values grouped for data
     *
     * @param $data
     * @return array
     */
    public function transformByData($data)
    {
        $results = array();

        foreach($data as $values) {

            $itemNumber = 0;

            $row = array();

            foreach($values['value'] as $key => $value) {
                if(strpos($key, 'item') !== false && strpos($key, 'typology') == false && strpos($key, 'label') == false) {
                    $itemNumber += 1;



                    $row['item-' . $itemNumber]['value'] = $value;
                    $row['item-' . $itemNumber]['label'] = $values['value'][$key . '-label'];
                    $row['item-' . $itemNumber]['typology'] = $values['value'][$key . '-typology'];

                    if($values['value'][$key . '-typology'] == 'direct-observation') {
                        $row['item-' . $itemNumber]['observationData'] = $this->calculateDirectObservationData($value);
                    }

                }

                $row['createdAt'] = $values['value']['createdAt']['date'];
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

    private function calculateDirectObservationData($value)
    {
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