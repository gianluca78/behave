<?php

namespace App\Controller;

use App\Form\Handler\CalendarFormHandler;
use App\Form\Type\CalendarType;
use App\Security\Encoder\OpenSslEncoder;
use App\Utility\Auth0Api;
use App\Utility\CouchDbData2Csv;
use App\Utility\CouchDbDataTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Form\Handler\ObservationFormHandler;
use App\Form\Handler\ObservationEditFormHandler;
use App\Form\Type\ObservationType;
use App\Form\Type\ObservationEditType;

use App\Entity\Observation;
use App\Entity\Student;
use App\CouchDb\Client as CouchDbClient;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @Route("/observation")
 *
 * Class ObservationController
 * @package App\Controller
 */
class ObservationController extends Controller
{
    CONST NEW_SUCCESS_STRING = 'Observation inserted successfully';
    CONST EDIT_SUCCESS_STRING = 'Observation edited successfully';
    CONST DATES_ADDED_SUCCESS = 'Dates added successfully';
    CONST DELETE_SUCCESS_STRING = 'Observation deleted successfully';
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
     * @Route("/student/{id}/list", name="observation_student_list")
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexStudentAction(Student $student, OpenSslEncoder $encoder)
    {
        $records = $this->getDoctrine()->getRepository('App\Entity\Observation')->findByStudentAndCreatorUserId(
            $student, $encoder->encrypt($this->getUser()->getUserId())
        );

        return array(
            'records' => $records,
            'title' => $this->get('translator')->trans(self::INDEX_TITLE),
            'student' => $student
        );
    }

    /**
    * @Route("/edit/{id}", name="observation_edit")
    * @Method({"GET", "POST"})  
    *
    * @param Request $request
    * @param Observation $observation
    * @param ObservationEditFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function editAction(Request $request, Observation $observation, ObservationEditFormHandler $formHandler)
    {
        if($observation->getStudent()->getCreatorUserId() != $this->getUser()->getUserId()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $form = $this->createForm(ObservationEditType::class, $observation, array(
            'action' => $this->generateUrl('observation_edit', array('id' => $observation->getId())),
            'creatorUserId' => $this->getUser()->getUserId()
        ));

        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_student_list', array(
                'id' => $observation->getStudent()->getId())
                )
            );
        }

        return $this->render('observation/edit.html.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->get('translator')->trans(self::EDIT_TITLE),
                'student' => $observation->getStudent()
            )
        );
    }

    /**
    * @Route("/new/{id}", name="observation_new")
    * @Method({"GET", "POST"})
    * @Template
    *
    * @param Request $request
    * @param ObservationFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function newAction(Request $request, Student $student, ObservationFormHandler $formHandler)
    {
        if($student->getCreatorUserId() != $this->getUser()->getUserId()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $entity = new Observation();
        $entity->setStudent($student);
        $entity->setCreatorUserId($this->getUser()->getUserId());

        $form = $this->createForm(ObservationType::class, $entity, array(
            'action' => $this->generateUrl('observation_new', array('id' => $student->getId())),
            'creatorUserId' => $this->getUser()->getUserId()
        ));

        
        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_student_list', array('id' => $student->getId())));
        }

        return array(
            'form' => $form->createView(),
            'title' => $this->get('translator')->trans(self::NEW_TITLE),
            'student' => $student
        );

    }

    /**
    * @Route("/number/{id}", name="observation_number")
    * @Method({"GET"})
    * @Template
    *
    * @param Observation $observation
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function numberAction(Observation $observation, CouchDbClient $couchDbClient)
    {
        $observationData = $couchDbClient->getObservationsById($observation->getId());

        return new Response(count(json_decode($observationData->getContents())->rows));

    }

    /**
    * @Route("/delete/{id}/{ids}", name="observation_delete")
    * @Method({"GET"})
    *
    * @param Request $request
    * @param Observation $entity
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function deleteAction(Request $request, Student $student)
    {
        $ids = json_decode($request->get('ids'), true);

        $em = $this->getDoctrine()->getManager();

        foreach($ids as $id) {
            $observation = $em->getRepository('App\Entity\Observation')->find($id);

            if($observation->getStudent()->getCreatorUserId() != $this->getUser()->getUserId()) {
                $response = new Response('not allowed');
                $response->setStatusCode(403);

                return $response;
            }

            $em->remove($observation);
            $em->flush();
        }

        $this->get('session')->getFlashbag()->add('success', $this->get('translator')->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('observation_student_list', array('id' => $student->getId())));
    }

    /**
     * @Route("/enableDisable/{id}/{ids}", name="observation_enable_disable")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Observation $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function enableDisableAction(Request $request, Student $student, \Swift_Mailer $mailer, Auth0Api $auth0Api)
    {
        $ids = json_decode($request->get('ids'), true);

        $em = $this->getDoctrine()->getManager();

        foreach($ids as $id) {
            $observation = $em->getRepository('App\Entity\Observation')->find($id);

            $isEnableNewValue = ($observation->getIsEnabled()) ? false : true;

            $observation->setIsEnabled($isEnableNewValue);

            $em->persist($observation);
            $em->flush();



            $observerEmail = $auth0Api->getUserByUsername($observation->getObserverUsername())[0]->email;

            if($observation->getIsEnabled()) {
                $message = (new \Swift_Message('[BEHAVE] Observation'))
                    ->setFrom('noreply@behaveproject.eu')
                    ->setTo($observerEmail)
                    ->setBody(
                        $this->renderView(
                            'emails/observation.html.twig',
                            array(
                                'givenName' => $auth0Api->getUserByUsername($observation->getObserverUsername())[0]->given_name,
                                'student' => $observation->getStudent(),
                                'behaviour' => $observation->getName(),
                                'observation' => $observation

                            )
                        ),
                        'text/html'
                    )
                    /*
                     * If you also want to include a plaintext version of the message
                    ->addPart(
                        $this->renderView(
                            'emails/registration.txt.twig',
                            array('name' => $name)
                        ),
                        'text/plain'
                    )
                    */
                ;

                try{
                    $mailer->send($message);
                }catch(\Swift_TransportException $e){
                    $response = $e->getMessage() ;
                }
            }

        }

        $this->get('session')->getFlashbag()->add('success', 'Observation(s) update with success');

        return $this->redirect($this->generateUrl('observation_student_list', array('id' => $student->getId())));
    }

    /**
     * @Route("/download-data/{id}", name="observation_download_data")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Observation $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadDataAction(Observation $observation, CouchDbClient $couchDbClient, CouchDbData2Csv $couchDbData2Csv, CouchDbDataTransformer $couchDbDataTransformer)
    {
        $rawData = $couchDbClient->getObservationsById($observation->getId());
        $rawData = json_decode($rawData->getContents(), true)['rows'];
        $rawData = $couchDbDataTransformer->transformByData($rawData);
        //dump($rawData);exit;

        $path = $this->container->getParameter('kernel.root_dir').'/Resources/tmp/raw-data.csv';

        $couchDbData2Csv->convert($rawData, $path);

        $response = new BinaryFileResponse($path);
        $response->headers->set('Content-type', 'text/csv');
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-Disposition', 'attachment; filename="raw-data.csv";');
        $response->sendHeaders();

        return $response;

    }
}