<?php
namespace App\Utility;

class CouchDbData2Csv {

    private $couchDbDataTransformer;

    public function __construct(CouchDbDataTransformer $couchDbDataTransformer)
    {
        $this->couchDbDataTransformer = $couchDbDataTransformer;
    }

    public function convert($rawData, $path)
    {
        $rows = array();

        if($rawData) {
            $rows[] = array_keys($rawData[0]);

            foreach($rawData as $record) {

                $row = array();

                foreach($record as $itemData) {
                    if(is_array($itemData)) {
                        switch($itemData['typology']) {
                            case 'choice-checkboxes':
                                $row[] = implode(',', $itemData['value']);

                                break;

                            case 'choice-radio':
                                $row[] = $itemData['value'];

                                break;


                            case 'direct-observation':
                                $row[] = $this->couchDbDataTransformer->calculateDirectObservationData($itemData['value']);

                                break;

                            case 'integer':
                            case 'range':
                            case 'text':
                                $row[] = $itemData['value'];

                                break;

                            case 'meter':
                                $row[] = $itemData['value']['xValue'] . ',' . $itemData['value']['yValue'];

                                break;

                        }
                    } else {
                        $row[] = $itemData;
                    }
                }

                $rows[] = $row;
            }

        }

        $this->saveTmpFileData($rows, $path);
    }

    private function saveTmpFileData($rows, $path)
    {
        $fp = fopen($path, "w");

        foreach ($rows as $line)
        {
            fputcsv(
                $fp, $line, ','
            );
        }

        fclose($fp);
    }
} 