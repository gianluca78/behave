<?php

namespace App\Controller;

use App\Utility\HighchartsGenerator;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Routing\Annotation\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Entity\Observation;
use App\Entity\ObservationDate;
use App\CouchDb\Client as CouchDbClient;
use GuzzleHTTP\Client as GuzzleClient;

/**
 * @Route("/calendar")
 *
 * Class CalendarController
 * @package App\Controller
 */
class CalendarController extends AbstractController
{
     CONST CALENDAR_VIEW_TITLE = '';

    /**
     * @Route("/update-date", name="calendar_update_observation_date", methods={"POST"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateObservationDateAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $em = $this->getDoctrine()->getManager();

        $startDates = json_decode($request->get('startDates'));
        $endDates = json_decode($request->get('endDates'));

        $observation = $this->getDoctrine()->getRepository('App\Entity\Observation')->find($request->get('observationId'));
        $observation->resetObservationDates();

        foreach($startDates as $key => $startDate) {
            $observationDate = new ObservationDate();
            $observationDate->setStartDateTimestamp(new \DateTime($startDate));
            $observationDate->setEndDateTimestamp(new \DateTime($endDates[$key]));

            $observation->addObservationDate($observationDate);
        }

        $em->persist($observation);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/{_locale}/{id}", name="calendar_view", methods={"GET"})
     * @Template
     *
     * @param Observation $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function calendarViewAction(Observation $observation)
    {
        if($observation->getStudent()->getCreatorUserId() != $this->getUser()->getUserId()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        return array(
            'title' => self::CALENDAR_VIEW_TITLE,
            'observation' => $observation
        );
    }
}