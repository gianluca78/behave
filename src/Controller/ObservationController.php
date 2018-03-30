<?php

namespace App\Controller;

use App\Form\Handler\CalendarFormHandler;
use App\Form\Type\CalendarType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Form\Handler\ObservationFormHandler;
use App\Form\Type\ObservationType;

use App\Entity\Observation;

/**
 * @Route("/observation")
 *
 * Class ObservationController
 * @package App\Controller
 */
class ObservationController extends Controller
{
    CONST NEW_SUCCESS_STRING = 'Record inserted successfully';
    CONST EDIT_SUCCESS_STRING = 'Record edited successfully';
    CONST DATES_ADDED_SUCCESS = 'Dates added successfully';
    CONST DELETE_SUCCESS_STRING = 'Record deleted successfully';
    CONST CALENDAR_TITLE = 'Pick your favourite dates';
    CONST NEW_TITLE = 'Insert new observation';
    CONST EDIT_TITLE = 'Edit observation';
    CONST INDEX_TITLE = 'List of observations';

    /**
     * @Route("/dates/{id}", name="observation_dates")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function calendarAction(Request $request, Observation $observation, CalendarFormHandler $calendarFormHandler)
    {
        $form = $this->createForm(CalendarType::class, null, array(
            'action' => $this->generateUrl('observation_dates', array('id' => $observation->getId())),
        ));

        $startTime = ($observation->getObservationDates()->count() > 0) ? $observation->getObservationDates()->first()->getStartDateTimestamp() : null;
        $endTime = ($observation->getObservationDates()->count() > 0) ? $observation->getObservationDates()->first()->getEndDateTimestamp() : null;

        $form->get('startTime')->setData($startTime);
        $form->get('endTime')->setData($endTime);

        if($calendarFormHandler->handle($form, $request, $observation, $this->get('translator')->trans(self::DATES_ADDED_SUCCESS))) {
            return $this->redirect($this->generateUrl('observation_list'));
        }

        return array(
            'form' => $form->createView(),
            'title' => self::CALENDAR_TITLE,
            'observation' => $observation
        );
    }

    /**
     * @Route("/list", name="observation_list")
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $records = $this->getDoctrine()->getRepository('App\Entity\Observation')->findAll();

        return array(
            'records' => $records,
            'title' => $this->get('translator')->trans(self::INDEX_TITLE)
        );
    }

    /**
    * @Route("/edit/{id}", name="observation_edit")
    * @Method({"GET", "POST"})  
    *
    * @param Request $request
    * @param Observation $observation
    * @param ObservationFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function editAction(Request $request, Observation $observation, ObservationFormHandler $formHandler)
    {
        $form = $this->createForm(ObservationType::class, $observation, array(
            'action' => $this->generateUrl('observation_edit', array('id' => $observation->getId())),
        ));

        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_list'));
        }

        return $this->render('observation/new.html.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->get('translator')->trans(self::EDIT_TITLE),
                'numberOfItems' => $observation->countItems()
            )
        );
    }

    /**
    * @Route("/new", name="observation_new")
    * @Method({"GET", "POST"})
    * @Template
    *
    * @param Request $request
    * @param ObservationFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function newAction(Request $request, ObservationFormHandler $formHandler)
    {
        $entity = new Observation();

        $form = $this->createForm(ObservationType::class, $entity, array(
            'action' => $this->generateUrl('observation_new')
        ));

        
        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_list'));
        }

        return array(
            'form' => $form->createView(),
            'title' => $this->get('translator')->trans(self::NEW_TITLE)
        );

            }

    /**
    * @Route("/delete/{id}", name="observation_delete")
    * @Method({"GET"})
    *
    * @param Request $request
    * @param Observation $entity
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function deleteAction(Request $request, Observation $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashbag()->add('success', $this->get('translator')->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('observation_list'));
    }
}