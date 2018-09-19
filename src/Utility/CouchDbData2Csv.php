<?php
namespace App\Utility;

class CouchDbData2Csv {

    private $labels = array();
    private $exclusions = array('_id', '_rev', 'observationId', 'userId');
    private $values = array();

    public function convert($rawData, $path)
    {
        $this->extractLabels($rawData);

        foreach($rawData as $record) {
            $row = array();

            foreach($record['value'] as $key => $value) {
                if(!in_array($key, $this->exclusions)) {

                    $value = (isset($value['date'])) ? $value['date'] : $value;
                    $value = is_array($value) ? $value['xValue'] . ', ' . $value['yValue'] : $value;

                    $row[] = $value;
                }
            }

            $this->values[] = $row;
        }

        $rows = array_merge($this->labels, $this->values);

        $this->saveTmpFileData($rows, $path);
    }

    private function extractLabels($rawData)
    {
        if(count($rawData) > 0) {
            $this->labels[] = array_diff(array_keys($rawData[0]['value']), $this->exclusions);
        }
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