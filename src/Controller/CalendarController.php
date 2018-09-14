<?php

namespace App\Controller;

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
use App\CouchDb\Client as CouchDbClient;
use GuzzleHTTP\Client as GuzzleClient;

/**
 * @Route("/calendar")
 *
 * Class CalendarController
 * @package App\Controller
 */
class CalendarController extends Controller
{
     CONST CALENDAR_VIEW_TITLE = '';

    /**
     * @Route("/{id}", name="calendar_view")
     * @Method({"GET"})
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