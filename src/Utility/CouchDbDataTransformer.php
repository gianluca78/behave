<?php
namespace App\Utility;


class CouchDbDataTransformer {

    public function transform($data)
    {
        $results = array();

        foreach($data as $values) {

            $itemNumber = 0;

            $row = array();

            foreach($values['value'] as $key => $value) {
                if(strstr($key, 'item')) {
                    $itemNumber += 1;

                    $row['item-' . $itemNumber] = $value;
                }

                $row['createdAt'] = $values['value']['createdAt']['date'];
            }

            $results[] = $row;

        }

        return $results;
    }
} 