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
     * @Route("/homepage", name="homepage")
     * @Method({"GET"})
     * @Template
     *
     */
    public function homepageAction()
    {
        if($this->getUser()) {
            return $this->forward('App\Controller\HomepageController::dashboardAction');
        }

        return array();
    }

    /**
     * @Route("/dashboard", name="dashboard")
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

        $dataToBeCategorized = array();

        foreach($observations as $observation) {
            $observationData = $couchDbClient->getObservationsById($observation->getId());

            $numberOfUncategorizedData = count(json_decode($observationData->getContents())->rows) - $observation->countCategorizedData();

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
            'dataToBeCategorized' => $dataToBeCategorized
        );
    }
}