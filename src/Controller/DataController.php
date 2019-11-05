<?php

namespace App\Controller;

use App\Utility\CouchDbDataTransformer;
use App\Utility\DataPreparationTool;
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
     * @Route("/{_locale}/analysis/{id}", name="data_analysis")
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
     * @Route("/{_locale}/analysis-results", name="data_analysis_results")
     * @Method({"POST"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function analysisResultsAction(Request $request, CouchDbClient $couchDbClient, EffectSizeChecker $effectSizeChecker, DataPreparationTool $dataPreparationTool) {
        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $config = array (
            'base_uri' => 'http://150.145.114.110/rtest/p'
        );

        $guzzle = new GuzzleClient($config);

        $fase = implode(',', array_fill(0, $request->get('selectedData')['phases'][0]['phase-count'],
                $request->get('selectedData')['phases'][0]['phase-name']
            ))
            . ',' . implode(',', array_fill(0, $request->get('selectedData')['phases'][1]['phase-count'],
                $request->get('selectedData')['phases'][1]['phase-name']
            ));

        $nomiFase = $request->get('selectedData')['phases'][0]['phase-name'] . ',' . $request->get('selectedData')['phases'][1]['phase-name'];

        $idData = $request->get('selectedData')['phases'][0]['phase-ids'] . ',' . $request->get('selectedData')['phases'][1]['phase-ids'];

        $rawData = $couchDbClient->getByIds(explode(',', $idData));
        $rawData = json_decode($rawData->getContents())->rows;

        //ordering data for timestamp ASC
        $dates = [];

        foreach($rawData as $key => $row) {
            $dates[$key] = $row->value->createdAt->date;
        }

        array_multisort($dates, SORT_ASC, $rawData);
        //end ordering

        $data = $dataPreparationTool->prepare($rawData, $request->get('selectedData')['items'], $request->get('selectedData')['operation']);

        $response = $guzzle->request('GET' , 'users', [ 'query' => [
                'data' => $data,
                'fase' => $fase,
                'nomifase' => $nomiFase
            ]
            ]
        );

        $contents = $response->getBody()->getContents();

        //remove unwanted and unexpected warning messages from the resulting JSON
        $positionJson = strpos($contents, '{  "outMonte"');
        $jsonContents = substr($contents, $positionJson);

        $data = json_decode($jsonContents);

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

        $templateVariables = array(
            'data' => $data,
            'analysisMessage' => $this->get('translator')->trans($effectSizeChecker->getResultMessage($data), array(), 'r_analysis'),
            'phasesLength' => array_count_values($data->database->PHASE),
            'interceptEstimate' => $data->regression->coefficients[0]->Estimate,
            'interceptStdError' => $data->regression->coefficients[0]->{'Std. Error'},
            'interceptTValue' => $data->regression->coefficients[0]->{'t value'},
            'interceptPr' => $data->regression->coefficients[0]->{'Pr(>|t|)'},
            'treatmentEstimate' => $data->regression->coefficients[1]->Estimate,
            'treatmentStdError' => $data->regression->coefficients[1]->{'Std. Error'},
            'treatmentTValue' => $data->regression->coefficients[1]->{'t value'},
            'treatmentPr' => $data->regression->coefficients[1]->{'Pr(>|t|)'},
            'r2' => $data->regression->{'r.squared'},
            'adjustedR2' => $data->regression->{'adj.r.squared'},
            'chart' => $chart
        );

        if(isset($data->regression->coeffients[2])) {
            $templateVariables['treatmentXTimeNaEstimate'] = $data->regression->coefficients[2]->Estimate;
            $templateVariables['treatmentXTimeNaStdError'] = $data->regression->coefficients[2]->{'Std. Error'};
            $templateVariables['treatmentXTimeNaTValue'] = $data->regression->coefficients[2]->{'t value'};
            $templateVariables['treatmentXTimeNaPr'] = $data->regression->coefficients[2]->{'Pr(>|t|)'};
        }

        return $templateVariables;
    }

    /**
     * @Route("/{_locale}/list/{id}", name="data_list")
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

        //ordering data for timestamp ASC
        $dates = [];

        foreach($rawPhaseData as $key => $row) {
            $dates[$key] = $row['value']['createdAt']['date'];
        }

        array_multisort($dates, SORT_ASC, $rawPhaseData);
        //end ordering

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
            'title' => $this->get('translator')->trans('Phase data'),
            'observation' => $observationPhase->getObservation(),
            'phaseData' => $phaseData,
            'chart' => $chart,
            'observationPhase' => $observationPhase
        );
    }
}