<?php
namespace App\Utility;

use Doctrine\ORM\EntityManagerInterface;

class DataPreparationTool
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function prepare($rawData, array $itemIds, $operation = null)
    {
        $data = [];

        foreach ($rawData as $observationData) {
            foreach ($itemIds as $item) {
                $itemId = 'item-' . $item['item-id'];

                $reverseScore = $item['reverse-score'];

                if (is_object($observationData->value->$itemId)) {
                    if (!$observationData->value->$itemId->typology && !$observationData->value->$itemId->counter) {
                        $numberOfSeconds = 0;

                        for ($i = 0; $i < count($observationData->value->$itemId->occurrenceTimestamps); $i += 2) {
                            $numberOfSeconds += $observationData->value->$itemId->occurrenceTimestamps[$i + 1] - $observationData->value->$itemId->occurrenceTimestamps[$i];
                        }

                        $data[$itemId][] = $numberOfSeconds;
                    } else {
                        $data[$itemId][] = $observationData->value->$itemId->counter;
                    }
                } else {
                    if($reverseScore == 'true') {
                        $item = $this->entityManager->getRepository('App\Entity\Item')->find($item['item-id']);

                        $options = explode(',', $item->getOptions());

                        $value = min($options) + max($options) - $observationData->value->$itemId;

                    } else {
                        $value = $observationData->value->$itemId;
                    }

                    $data[$itemId][] = $value;
                }
            }
        }

        if(count($data) == 1) {
            return implode(',', reset($data));
        }

        if(count($data) > 1) {
            $calculatedData = [];

            foreach($data as $itemData) {
                foreach($itemData as $key => $value) {
                    if(isset($calculatedData[$key])) {
                        switch ($operation) {
                            case 'sum':
                                $calculatedData[$key]+= $value;
                                break;

                            case 'mean':
                                $calculatedData[$key] = ($calculatedData[$key] + $value) / 2;
                                break;
                        }
                    } else {
                        $calculatedData[$key]= $value;
                    }
                }
            }

            return implode(',', $calculatedData);
        }
    }
}