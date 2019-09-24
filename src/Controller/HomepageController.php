<?php

namespace App\Controller;

use App\Security\Encoder\OpenSslEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Entity\Observation;
use App\CouchDb\Client as CouchDbClient;

/**
 * @Route("/")
 *
 * Class HomepageController
 * @package App\Controller
 */
class HomepageController extends Controller
{
    /**
     * @Route("/{_locale}/homepage", name="homepage", requirements={"locale": "en|it"})
     * @Method({"GET"})
     * @Template
     *
     */
    public function homepageAction(Request $request)
    {
        if($this->getUser()) {
            return $this->forward('App\Controller\HomepageController::dashboardAction', array(
                '_route' => $request->attributes->get('_route'),
                '_route_params' => $request->attributes->get('_route_params')
            ));
        }

        return array();
    }

    /**
     * @Route("/{_locale}/dashboard", name="dashboard", requirements={"locale": "en|it"})
     * @Method({"GET"})
     * @Template
     *
     */
    public function dashboardAction(OpenSslEncoder $encoder, CouchDbClient $couchDbClient)
    {
        $futureObservationDates = $this->getDoctrine()->getRepository('App\Entity\ObservationDate')
            ->findFutureObservations($encoder->encrypt($this->getUser()->getUserId()));

        $observationsWithoutDates = $this->getDoctrine()->getRepository('App\Entity\Observation')
            ->findWithoutDatesByCreatorUserId($encoder->encrypt($this->getUser()->getUserId()));

        $observations = $this->getDoctrine()->getRepository('App\Entity\Observation')
            ->findActiveObservationsByCreatorUserId($encoder->encrypt($this->getUser()->getUserId()));

        $numberOfStudents = $this->getDoctrine()->getRepository('App\Entity\Student')->countStudentsByUserId(
            $encoder->encrypt($this->getUser()->getUserId())
        );

        $numberOfMeasures = $this->getDoctrine()->getRepository('App\Entity\Measure')->countMeasuresByUserId(
            $this->getUser()->getUserId()
        );

        $dataToBeCategorized = array();

        $numberOfAllData = 0;
        $numberOfAllUncategorizedData = 0;

        foreach($observations as $observation) {
            $observationData = $couchDbClient->getObservationsById($observation->getId());

            $numberOfAllObservations = count(json_decode($observationData->getContents())->rows);
            $numberOfUncategorizedData = $numberOfAllObservations - $observation->countCategorizedData();

            $numberOfAllData += $numberOfAllObservations;
            $numberOfAllUncategorizedData += $numberOfUncategorizedData;

            if($numberOfUncategorizedData > 0) {
                $dataToBeCategorized[] = array(
                    'student' => $observation->getStudent(),
                    'observation' => $observation,
                    'numberOfUncategorizedData' => $numberOfUncategorizedData
                );
            }
        }

        return array(
            'observationDates' => $futureObservationDates,
            'observationsWithoutDates' => $observationsWithoutDates,
            'dataToBeCategorized' => $dataToBeCategorized,
            'numberOfStudents' => $numberOfStudents,
            'numberOfMeasures' => $numberOfMeasures,
            'percentageCategorizedData' => ($numberOfAllData == 0) ? 'N. A.' : 100 - (int) ($numberOfAllUncategorizedData / $numberOfAllData * 100)
        );
    }
}