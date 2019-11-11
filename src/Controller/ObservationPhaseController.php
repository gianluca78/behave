<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use Symfony\Contracts\Translation\TranslatorInterface;

use App\Form\Handler\ObservationPhaseFormHandler;
use App\Form\Type\ObservationPhaseType;

use App\Entity\Observation;
use App\Entity\ObservationPhase;
use App\CouchDb\Client as CouchDbClient;

/**
 * @Route("/observation-phase")
 *
 * Class ObservationPhaseController
 * @package App\Controller
 */
class ObservationPhaseController extends AbstractController
{
    CONST NEW_SUCCESS_STRING = 'Observation phase inserted successfully';
    CONST EDIT_SUCCESS_STRING = 'Observation phase edited successfully';
    CONST DELETE_SUCCESS_STRING = 'Observation phase deleted successfully';
    CONST NEW_TITLE = 'Insert new observation phase';
    CONST EDIT_TITLE = 'Edit observation phase';
    CONST INDEX_TITLE = 'List of observation dates';

    /**
     * @Route("/{_locale}/list/{id}", name="observation_phase_list", methods={"GET"})
     * @Template
     *
     * @param Request $request
     * @param Observation $observation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Observation $observation, CouchDbClient $couchDbClient, TranslatorInterface $translator)
    {
        if($observation->getStudent()->getCreatorUserId() != $this->getUser()->getUserId()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $observationData = $couchDbClient->getObservationsById($observation->getId());

        $records = $this->getDoctrine()->getRepository('App\Entity\ObservationPhase')->findByObservation($observation);

        return array(
            'records' => $records,
            'title' => $translator->trans(self::INDEX_TITLE),
            'observation' => $observation,
            'observationData' => json_decode($observationData->getContents())->rows,
            'observationPhases' => $observation->getObservationPhases()
        );
    }

    /**
     * @Route("/{_locale}/new/{id}", name="observation_phase_new", methods={"GET", "POST"})
     * @Template
     *
     * @param Request $request
     * @param ObservationPhaseFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Observation $observation, ObservationPhaseFormHandler $formHandler, TranslatorInterface $translator)
    {
        $entity = new ObservationPhase();
        $entity->setObservation($observation);

        $form = $this->createForm(ObservationPhaseType::class, $entity, array(
            'action' => $this->generateUrl('observation_phase_new', array('id' => $observation->getId()))
        ));

        if($formHandler->handle($form, $request, $translator->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_phase_list', array('id' => $observation->getId())));
        }

        return array(
            'form' => $form->createView(),
            'title' => $translator->trans(self::NEW_TITLE),
            'observation' => $observation
        );
    }

    /**
     * @Route("/{_locale}/edit/{id}", name="observation_phase_edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param ObservationPhase $observationPhase
     * @param ObservationPhaseFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ObservationPhase $observationPhase, ObservationPhaseFormHandler $formHandler, TranslatorInterface $translator)
    {
        $form = $this->createForm(ObservationPhaseType::class, $observationPhase, array(
            'action' => $this->generateUrl('observation_phase_edit', array('id' => $observationPhase->getId()))
        ));

        if($formHandler->handle($form, $request, $translator->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_phase_list', array(
                        'id' => $observationPhase->getObservation()->getId())
                )
            );
        }

        return $this->render('observation_phase/new.html.twig',
            array(
                'form' => $form->createView(),
                'title' => $translator->trans(self::EDIT_TITLE),
                'observation' => $observationPhase->getObservation()
            )
        );
    }

    /**
     * @Route("/delete/{id}", name="observation_phase_delete", methods={"GET"})
     *
     * @param Request $request
     * @param ObservationPhase $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, ObservationPhase $entity, TranslatorInterface $translator)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashbag()->add('success', $translator->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('observation_phase_list', array(
            'id' => $entity->getObservation()->getId()
        )));
    }

    /**
     * @Route("/delete-raw-data/{id}/{ids}", name="observation_phase_delete_raw_data", requirements={"ids": "[a-zA-Z0-9\/]+"}, methods={"GET"})
     *
     * @param Request $request
     * @param Observation $observation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteRawAction(Observation $observation, $ids, CouchDbClient $couchDbClient, TranslatorInterface $translator)
    {
        $ids = explode('/', $ids);

        $em = $this->getDoctrine()->getManager();

        $phases = $this->getDoctrine()
            ->getRepository(ObservationPhase::class)
            ->findByObservation($observation);

        foreach($phases as $phase) {
            foreach($ids as $id) {
                if($phase->hasDataId($id)) {
                    $phase->removeDataId($id);

                    $em->persist($phase);
                    $em->flush();

                    //@TODO add a control
                    $data = $couchDbClient->getObservationById($id);
                    $data = json_decode($data->getContents(), true)['rows'];

                    $rev = $data[0]['value']['_rev'];

                    $couchDbClient->delete($id, $rev);
                }
            }
        }

        $this->get('session')->getFlashbag()->add('success', $translator->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('observation_phase_list', array(
            'id' => $observation->getId()
        )));
    }

    /**
     * @Route("/has-data-id/{dataId}", name="observation_phase_has_data_id", methods={"GET"})
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
     * @Route("/save-observation-phase-data/{id}", name="observation_phase_save_observation_phase_data", methods={"POST"})
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
                 $phase->setDataIds(array_unique(array_merge($request->get('ids'), $phase->getDataIds())));

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