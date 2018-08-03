<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Form\Handler\ObservationPhaseFormHandler;
use App\Form\Type\ObservationPhaseType;

use App\Entity\Observation;
use App\Entity\ObservationPhase;

/**
 * @Route("/observation-phase")
 *
 * Class ObservationPhaseController
 * @package App\Controller
 */
class ObservationPhaseController extends Controller
{
    CONST NEW_SUCCESS_STRING = 'Observation phase inserted successfully';
    CONST EDIT_SUCCESS_STRING = 'Observation phase edited successfully';
    CONST DELETE_SUCCESS_STRING = 'Observation phase deleted successfully';
    CONST NEW_TITLE = 'Insert new observation date';
    CONST EDIT_TITLE = 'Edit observation date';
    CONST INDEX_TITLE = 'List of observation dates';

    /**
     * @Route("/list/{id}", name="observation_phase_list")
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     * @param Observation $observation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Observation $observation)
    {
        $records = $this->getDoctrine()->getRepository('App\Entity\ObservationPhase')->findByObservation($observation);

        return array(
            'records' => $records,
            'title' => $this->get('translator')->trans(self::INDEX_TITLE),
            'observation' => $observation
        );
    }

    /**
     * @Route("/new/{id}", name="observation_phase_new")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     * @param ObservationPhaseFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Observation $observation, ObservationPhaseFormHandler $formHandler)
    {
        $entity = new ObservationPhase();
        $entity->setObservation($observation);

        $form = $this->createForm(ObservationPhaseType::class, $entity, array(
            'action' => $this->generateUrl('observation_phase_new', array('id' => $observation->getId()))
        ));

        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_phase_list', array('id' => $observation->getId())));
        }

        return array(
            'form' => $form->createView(),
            'title' => $this->get('translator')->trans(self::NEW_TITLE)
        );
    }

    /**
     * @Route("/edit/{id}", name="observation_phase_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param ObservationPhase $observationPhase
     * @param ObservationPhaseFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ObservationPhase $observationPhase, ObservationPhaseFormHandler $formHandler)
    {
        $form = $this->createForm(ObservationPhaseType::class, $observationPhase, array(
            'action' => $this->generateUrl('observation_phase_edit', array('id' => $observationPhase->getId()))
        ));

        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_phase_list', array(
                        'id' => $observationPhase->getObservation()->getId())
                )
            );
        }

        return $this->render('observation_phase/new.html.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->get('translator')->trans(self::EDIT_TITLE)
            )
        );
    }

    /**
     * @Route("/delete/{id}", name="observation_phase_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param ObservationPhase $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, ObservationPhase $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashbag()->add('success', $this->get('translator')->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('observation_phase_list', array(
            'id' => $entity->getObservation()->getId()
        )));
    }

    /**
     * @Route("/has-data-id/{dataId}", name="observation_phase_has_data_id")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param ObservationPhase $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function hasDataIdAction(Request $request)
    {
        $observationPhase = $this->getDoctrine()
            ->getRepository('App\Entity\ObservationPhase')
            ->findByDataId($request->get('dataId')
            );

        return new Response((string) $observationPhase);
    }


    /**
     * @Route("/save-observation-phase-data/{id}", name="observation_phase_save_observation_phase_data")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param ObservationPhase $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
     public function saveObservationPhaseDataAction(Request $request, Observation $observation)
     {
         if(!$request->isXmlHttpRequest()) {
             $response = new Response('not allowed');
             $response->setStatusCode(403);

             return $response;
         }

         $phases = $this->getDoctrine()
             ->getRepository(ObservationPhase::class)
             ->findByObservation($observation);

         foreach($phases as $phase) {
             if($phase->getId() == $request->get('phaseId')) {
                 $phase->setDataIds(array_merge($request->get('ids'), $phase->getDataIds()));

                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($phase);
                 $entityManager->flush();
             } else {
                 foreach($request->get('ids') as $id) {
                    $phase->removeDataId($id);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($phase);
                    $entityManager->flush();
                 }
             }
         }

         return new Response();
     }
}