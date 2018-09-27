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
     * @Route("/analysis/{id}", name="data_analysis")
     * @Method({"GET", "POST"})
     * @Template
     *
     */
    public function analysisAction(Observation $observation, Request $request, CouchDbClient $couchDbClient, EffectSizeChecker $effectSizeChecker)
    {
        $config = array (
            'base_uri' => 'http://150.145.114.110/rtest/p'
        );

        $guzzle = new GuzzleClient($config);
        $twigVariables = array();

        if ($request->isMethod('POST')) {
            $formData = $request->request->all();

            $itemId = 'item-' . $formData['item'];

            unset($formData['item']);

            $phaseNames = array_keys($formData);

            $fase = implode(',', array_fill(0, count(explode(',', $formData[$phaseNames[0]])), $phaseNames[0]))
                . ',' . implode(',', array_fill(0, count(explode(',', $formData[$phaseNames[1]])), $phaseNames[1]));

            $data = '';

            $idData = $formData[$phaseNames[0]] . ',' . $formData[$phaseNames[1]];

            $rawData = $couchDbClient->getByIds(explode(',', $idData));
            $rawData = json_decode($rawData->getContents())->rows;

            foreach($rawData as $key => $observationData) {
                $data.= $observationData->value->$itemId;

                if($key != count($rawData) -1) {
                    $data.= ',';
                }
            }

            $response = $guzzle->request('GET' , 'users', [ 'query' => [
                    //'data' => '7,4,3,5,3,3,3,3',
                    //'data' => '7,4,3,5,3,3,7,4',
                    'data' => $data,
                    'fase' => $fase
                ]
                ]
            );

            $data = json_decode($response->getBody()->getContents());

            $series = array();
            $phaseNameLoopIndex = 0;
            $dataLoopIndex = 0;
            $lastPhaseName = '';
            $countPhases = array_count_values($data->database->PHASE);

            foreach($countPhases as $phaseName => $count) {
                $series[] = array('name' => $phaseName);

                if($phaseNameLoopIndex > 0) {
                    $count+= $countPhases[$lastPhaseName];
                }

                $lastPhaseName = $phaseName;

                for($i=$dataLoopIndex; $i<$count; $i++) {
                    $series[$phaseNameLoopIndex]['data'][] = $data->database->DV[$i];

                    $dataLoopIndex++;
                }

                $phaseNameLoopIndex++;

            }

            //var_dump($series);exit;
            //var_dump($data); exit;
            //var_dump(array_count_values($data->database->PHASE)); exit;

            $highchartsGenerator = new HighchartsGenerator($this->get('translator'));
            $chart = $highchartsGenerator->generateScatterPlot(
                'Scatter plot (observation name)',
                /*array(
                    array("name" => "Baseline",
                        "data" => array(1,2,4,5,6,3,8)
                    ),
                    array("name" => "Intervention",
                        "data" => array(1,52,45,5,63,3,8)
                    ),
                ),*/
                $series,
                'linechart',
                'x',
                'y'
            );

            $twigVariables = array(
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

        /*
        $response = $guzzle -> request('GET' , 'users', [ 'query' => [
                'data' => '24.7443762781186,25.2927400468384,16.953316953317,24.1448692152917,17.7062374245473,25.5747126436782,30.1204819277108,15.0763358778626,37.9816513761468,38.423645320197,31.4732142857143,25.9162303664921,27.6497695852535,39.0946502057613,13.189448441247,25.8893280632411,32.4263038548753,40.4564315352697,34.341252699784,18.7660668380463,21.4622641509434,25.6565656565657,28.3292978208232,38.25',
                'fase' => 'A,A,A,A,A,A,A,A,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B'
                ]
            ]
        );

        $response = $guzzle -> request('GET' , 'users', [ 'query' => [
                'data' => '0.5,0.5,0,0,0.5,0.5,0.5,0.5,0.5,0.5,0,0,0,0,0,0.5,0.5,0.5,0.5,0.5,0.5,0.5,0.5,0.5,0.5,0.5,0.5,0,0.5,0.5,0,0.5,0.5,1.5,0,1,1,0,0,0,0,2,1.5,0,0,1,1,0.5,0,0,1.5,0,0,0,0,0,1,1,1,1,0.5,1,1,0,1,1,1,1,1,1.5,1,5,4.5,2,3.5,1,1,4,5,3.5,5,5,3,1,4.5,5,6,4,2,1,1.5,1.5,0.5,0,4,5.5,4,4,5.5,6,6,5,8.5,5,7.5,7.5,5.5,4,6,6.5',
                'fase' => 'A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,A,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B,B'
                ]
            ]
        );*/

        return array_merge(array(
            'observation' => $observation,
            'phases' => $observation->getObservationPhases(),
            'measure' => $observation->getMeasure(),
            'title' => 'Data analysis'
        ), $twigVariables);
    }
}