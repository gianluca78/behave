<?php

namespace App\Controller;

use App\Utility\CouchDbDataTransformer;
use App\Utility\EffectSizeChecker;
use App\Utility\HighchartsGenerator;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Entity\Observation;
use App\Entity\ObservationPhase;
use App\CouchDb\Client as CouchDbClient;
use GuzzleHTTP\Client as GuzzleClient;

/**
 * @Route("/data")
 *
 * Class DataController
 * @package App\Controller
 */
class DataController extends Controller
{
    /**
     * @Route("/analysis/{id}", name="data_analysis")
     * @Method({"GET", "POST"})
     * @Template
     *
     */
    public function analysisAction(Observation $observation, CouchDbClient $couchDbClient, CouchDbDataTransformer $couchDbDataTransformer, EffectSizeChecker $effectSizeChecker)
    {
        $gatheredData = $couchDbClient->getObservationsById($observation->getId());
        $gatheredData = json_decode($gatheredData->getContents(), true)['rows'];

        $items = $couchDbDataTransformer->transformByData($gatheredData, false);

        return array(
            'items' => $items,
            'observation' => $observation,
            'phases' => $observation->getObservationPhases(),
            'measure' => $observation->getMeasure(),
            'title' => 'Data analysis'
        );
    }

    /**
     * @Route("/analysis-results", name="data_analysis_results")
     * @Method({"POST"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function analysisResultsAction(Request $request, CouchDbClient $couchDbClient, EffectSizeChecker $effectSizeChecker) {
        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $config = array (
            'base_uri' => 'http://150.145.114.110/rtest/p'
        );

        $guzzle = new GuzzleClient($config);
        $itemId = 'item-' . $request->get('selectedData')['item-id'];

        $fase = implode(',', array_fill(0, $request->get('selectedData')['phases'][0]['phase-count'],
                $request->get('selectedData')['phases'][0]['phase-name']
                ))
                . ',' . implode(',', array_fill(0, $request->get('selectedData')['phases'][1]['phase-count'],
                $request->get('selectedData')['phases'][1]['phase-name']
                ));

        $data = '';

        $idData = $request->get('selectedData')['phases'][0]['phase-ids'] . ',' . $request->get('selectedData')['phases'][1]['phase-ids'];

        $rawData = $couchDbClient->getByIds(explode(',', $idData));
        $rawData = json_decode($rawData->getContents())->rows;


        foreach($rawData as $key => $observationData) {
            //PORCHERIA
            if(is_object($observationData->value->$itemId)) {
                if(!$observationData->value->$itemId->typology && !$observationData->value->$itemId->counter) {
                    $numberOfSeconds = 0;

                    for($i=0; $i<count($observationData->value->$itemId->occurrenceTimestamps); $i+=2) {
                        $numberOfSeconds += $observationData->value->$itemId->occurrenceTimestamps[$i + 1] - $observationData->value->$itemId->occurrenceTimestamps[$i];
                    }

                    $data.= $numberOfSeconds;

                } else {
                    $data .= $observationData->value->$itemId->counter;
                }
            } else {
                $data.= $observationData->value->$itemId;
            }

            if($key != count($rawData) -1) {
                $data.= ',';
            }
        }

        $nomiFase = $request->get('selectedData')['phases'][0]['phase-name'] . ',' . $request->get('selectedData')['phases'][1]['phase-name'];

        $response = $guzzle->request('GET' , 'users', [ 'query' => [
                'data' => $data,
                'fase' => $fase,
                'nomifase' => $nomiFase
            ]
            ]
        );

        $data = json_decode($response->getBody()->getContents());

        $series = array();
        $phaseNameLoopIndex = 0;
        $dataLoopIndex = 0;
        $lastPhaseName = '';
        $countPhases = array_count_values($data->database->PHASE);

        $countPhases = array_combine(
            array($request->get('selectedData')['phases'][0]['phase-name'],
                $request->get('selectedData')['phases'][1]['phase-name']
            ), $countPhases
        );

        //var_dump(date('Y-m-d', strtotime($rawData[1]->value->createdAt->date))); exit;

        foreach($countPhases as $phaseName => $count) {
            $series[] = array('name' => $phaseName);

            if($phaseNameLoopIndex > 0) {
                $count+= $countPhases[$lastPhaseName];
            }

            $lastPhaseName = $phaseName;

            for($i=$dataLoopIndex; $i<$count; $i++) {
                $series[$phaseNameLoopIndex]['data'][] = array(
                    'x' => $i,
                    'y' => $data->database->DV[$i]
                );

                $dataLoopIndex++;
            }

            $phaseNameLoopIndex++;

        }

        $highchartsGenerator = new HighchartsGenerator($this->get('translator'));
        $chart = $highchartsGenerator->generateScatterPlot(
            $request->get('selectedData')['observation-name'],
            $series,
            'linechart',
            'Session',
            'Value',
            true
        );

        return array(
            'data' => $data,
            'analysisMessage' => $effectSizeChecker->getResultMessage($data),
            'phasesLength' => array_count_values($data->database->PHASE),
            'interceptEstimate' => $data->regression->coefficients[0]->Estimate,
            'interceptStdError' => $data->regression->coefficients[0]->{'Std. Error'},
            'interceptTValue' => $data->regression->coefficients[0]->{'t value'},
            'interceptPr' => $data->regression->coefficients[0]->{'Pr(>|t|)'},
            'treatmentEstimate' => $data->regression->coefficients[1]->Estimate,
            'treatmentStdError' => $data->regression->coefficients[1]->{'Std. Error'},
            'treatmentTValue' => $data->regression->coefficients[1]->{'t value'},
            'treatmentPr' => $data->regression->coefficients[1]->{'Pr(>|t|)'},
            'treatmentXTimeNaEstimate' => $data->regression->coefficients[2]->Estimate,
            'treatmentXTimeNaStdError' => $data->regression->coefficients[2]->{'Std. Error'},
            'treatmentXTimeNaTValue' => $data->regression->coefficients[2]->{'t value'},
            'treatmentXTimeNaPr' => $data->regression->coefficients[2]->{'Pr(>|t|)'},
            'r2' => $data->regression->{'r.squared'},
            'adjustedR2' => $data->regression->{'adj.r.squared'},
            'chart' => $chart
        );
    }

    /**
     * @Route("/list/{id}", name="data_list")
     * @Method({"GET"})
     * @Template
     *
     * @param ObservationPhase $observationPhase
     * @param CouchDbClient $couchDbClient
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dataListAction(ObservationPhase $observationPhase, CouchDbClient $couchDbClient, CouchDbDataTransformer $couchDbDataTransformer)
    {
        if($observationPhase->getObservation()->getStudent()->getCreatorUserId() != $this->getUser()->getUserId()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $rawPhaseData = $couchDbClient->getByIds($observationPhase->getDataIds());
        $rawPhaseData = json_decode($rawPhaseData->getContents(), true)['rows'];
        $phaseData = $couchDbDataTransformer->transformByData($rawPhaseData);
        $chartData = $couchDbDataTransformer->transformByNameAndData($rawPhaseData);

        $highchartsGenerator = new HighchartsGenerator($this->get('translator'));
        $chart = $highchartsGenerator->generateScatterPlot(
            $observationPhase->getObservation()->getName(),
            $chartData,
            'linechart',
            'x',
            'y'
        );

        return array(
            'title' => 'Phase data',
            'observation' => $observationPhase->getObservation(),
            'phaseData' => $phaseData,
            'chart' => $chart,
            'observationPhase' => $observationPhase
        );
    }

    /**
     * @Route("/new-aggregated-index/{id}", name="data_new_aggregated_index")
     * @Method({"GET"})
     * @Template
     *
     * @param Observation $observation
     * @param CouchDbClient $couchDbClient
     * @param CouchDbDataTransformer $couchDbDataTransformer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAggregatedIndexAction(Observation $observation, CouchDbClient $couchDbClient, CouchDbDataTransformer $couchDbDataTransformer)
    {
        $gatheredData = $couchDbClient->getObservationsById($observation->getId());
        $gatheredData = json_decode($gatheredData->getContents(), true)['rows'];

        $items = $couchDbDataTransformer->transformByData($gatheredData, false);



        return array(
            'items' => $items,
            'observation' => $observation,
            'title' => 'New aggregated index'
        );
    }
}