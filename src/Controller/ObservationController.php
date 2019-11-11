<?php

namespace App\Controller;

use App\CouchDb\Client;
use App\Form\Handler\CalendarFormHandler;
use App\Form\Type\CalendarType;
use App\Form\Type\ObservationNotificationEmailsType;
use App\Security\Encoder\OpenSslEncoder;
use App\Utility\Auth0Api;
use App\Utility\CouchDbData2Csv;
use App\Utility\CouchDbDataTransformer;
use Symfony\Component\Routing\Annotation\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use Symfony\Contracts\Translation\TranslatorInterface;

use App\Form\Handler\ObservationFormHandler;
use App\Form\Handler\ObservationEditFormHandler;
use App\Form\Type\ObservationType;
use App\Form\Type\ObservationEditType;

use App\Entity\Observation;
use App\Entity\Student;
use App\CouchDb\Client as CouchDbClient;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/observation")
 *
 * Class ObservationController
 * @package App\Controller
 */
class ObservationController extends AbstractController
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
     * @Route("/dates/{id}", name="observation_dates", methods={"GET", "POST"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function calendarAction(Request $request, Observation $observation, CalendarFormHandler $calendarFormHandler, TranslatorInterface $translator)
    {
        $form = $this->createForm(CalendarType::class, null, array(
            'action' => $this->generateUrl('observation_dates', array('id' => $observation->getId())),
        ));

        $startTime = ($observation->getObservationDates()->count() > 0) ? $observation->getObservationDates()->first()->getStartDateTimestamp() : null;
        $endTime = ($observation->getObservationDates()->count() > 0) ? $observation->getObservationDates()->first()->getEndDateTimestamp() : null;

        $form->get('startTime')->setData($startTime);
        $form->get('endTime')->setData($endTime);

        if($calendarFormHandler->handle($form, $request, $observation, $translator->trans(self::DATES_ADDED_SUCCESS))) {
            return $this->redirect($this->generateUrl('observation_list'));
        }

        return array(
            'form' => $form->createView(),
            'title' => self::CALENDAR_TITLE,
            'observation' => $observation
        );
    }

    /**
     * @Route("/list", name="observation_list", methods={"GET"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, TranslatorInterface $translator)
    {
        $records = $this->getDoctrine()->getRepository('App\Entity\Observation')->findAll();

        return array(
            'records' => $records,
            'title' => $translator->trans(self::INDEX_TITLE)
        );
    }

    /**
     * @Route("/{_locale}/student/{id}/list", name="observation_student_list", requirements={"locale": "en|it"}, methods={"GET"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexStudentAction(Student $student, OpenSslEncoder $encoder, Request $request, TranslatorInterface $translator)
    {
        $records = $this->getDoctrine()->getRepository('App\Entity\Observation')->findByStudentAndCreatorUserId(
            $student, $encoder->encrypt($this->getUser()->getUserId())
        );

        $form = $this->createForm(ObservationNotificationEmailsType::class, null, array());

        return array(
            'form' => $form->createView(),
            'records' => $records,
            'title' => $translator->trans(self::INDEX_TITLE),
            'student' => $student,
            'baseUrl' => $request->server->get('HTTP_HOST')
        );
    }

    /**
    * @Route("/{_locale}/edit/{id}", name="observation_edit", methods={"GET", "POST"})
    *
    * @param Request $request
    * @param Observation $observation
    * @param ObservationFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function editAction(Request $request, Observation $observation, ObservationFormHandler $formHandler, TranslatorInterface $translator)
    {
        if($observation->getStudent()->getCreatorUserId() != $this->getUser()->getUserId()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $form = $this->createForm(ObservationType::class, $observation, array(
            'action' => $this->generateUrl('observation_edit', array('id' => $observation->getId())),
            'creatorUserId' => $this->getUser()->getUserId()
        ));

        if($formHandler->handle($form, $request, $translator->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_student_list', array(
                'id' => $observation->getStudent()->getId())
                )
            );
        }

        return $this->render('observation/new.html.twig',
            array(
                'observation' => $observation,
                'form' => $form->createView(),
                'title' => $translator->trans(self::EDIT_TITLE),
                'student' => $observation->getStudent()
            )
        );
    }

    /**
    * @Route("/{_locale}/new/{id}", name="observation_new", methods={"GET", "POST"})
    * @Template
    *
    * @param Request $request
    * @param ObservationFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function newAction(Request $request, Student $student, ObservationFormHandler $formHandler, TranslatorInterface $translator)
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

        
        if($formHandler->handle($form, $request, $translator->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_student_list', array('id' => $student->getId())));
        }

        return array(
            'observation' => $entity,
            'form' => $form->createView(),
            'title' => $translator->trans(self::NEW_TITLE),
            'student' => $student
        );

    }

    /**
    * @Route("/number/{id}", name="observation_number", methods={"GET"})
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
    * @Route("/delete/{id}/{ids}", name="observation_delete", methods={"GET"})
    *
    * @param Request $request
    * @param Observation $entity
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function deleteAction(Request $request, Student $student, Client $couchDbClient, TranslatorInterface $translator)
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


            $data = $couchDbClient->getObservationsById($observation->getId());
            $data = json_decode($data->getContents(), true)['rows'];

            foreach($data as $document) {
                $id = $document['value']['_id'];
                $rev = $document['value']['_rev'];

                $couchDbClient->delete($id, $rev);
            }

            $em->remove($observation);
            $em->flush();

        }

        $this->get('session')->getFlashbag()->add('success', $translator->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('observation_student_list', array('id' => $student->getId())));
    }

    /**
     * @Route("/enableDisable/{id}", name="observation_enable_disable", methods={"GET"})
     *
     * @param Request $request
     * @param Observation $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function enableDisableAction(Request $request, Observation $observation, \Swift_Mailer $mailer, Auth0Api $auth0Api)
    {
        $em = $this->getDoctrine()->getManager();
        $isEnableNewValue = ($observation->getIsEnabled()) ? false : true;

        $observation->setIsEnabled($isEnableNewValue);

        $em->persist($observation);
        $em->flush();

        $observersEmails = $observation->getNotificationEmails();

        if($observation->getIsEnabled() && is_array($observersEmails)) {

            foreach($observersEmails as $email) {
                $message = (new \Swift_Message('[BEHAVE] Observation'))
                    ->setFrom('noreply@behaveproject.eu')
                    ->setTo($email)
                    ->setBody(
                        $this->renderView(
                            'emails/observation.html.twig',
                            array(
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

        return $this->redirect($this->generateUrl('observation_student_list', array('id' => $observation->getStudent()->getId())));
    }

    /**
     * @Route("/download-data/{id}", name="observation_download_data", methods={"GET"})
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

        $path = $this->getParameter('kernel.root_dir').'/Resources/tmp/raw-data.csv';

        $couchDbData2Csv->convert($rawData, $path);

        $response = new BinaryFileResponse($path);
        $response->headers->set('Content-type', 'text/csv');
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-Disposition', 'attachment; filename="raw-data.csv";');
        $response->sendHeaders();

        return $response;

    }

    /**
     * @Route("/delete-notification-email", name="observation_delete_notification_email", methods={"GET"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteNotificationEmails(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $observation = $em->getRepository('App\Entity\Observation')->find($request->get('observationId'));
        $observation->deleteNotificationEmail($request->get('email'));

        $em->persist($observation);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/get-notification-emails", name="observation_get_notification_emails", methods={"GET"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getNotificationEmailsAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $em = $this->getDoctrine()->getManager();

        $observation = $em->getRepository('App\Entity\Observation')->find($request->get('observationId'));

        $result = '';

        if($observation->getNotificationEmails()) {
            foreach($observation->getNotificationEmails() as $email) {
                $result.= '<li class="list-group-item">' . $email . '<a name="Click here to remove the address" class="fa fa-trash remove-email"></a></li>';
            }
        }

        return new Response($result);
    }

    /**
     * @Route("/has-scheduled-dates", name="observation_has_scheduled_dates", methods={"GET"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function hasScheduledDatesAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $em = $this->getDoctrine()->getManager();

        $observation = $em->getRepository('App\Entity\Observation')->find($request->get('observationId'));

        $result = ($observation->getObservationScheduler() && !$observation->getObservationScheduler()->getHasDates()) ? 'false' : 'true';

        return new Response($result);
    }


    /**
     * @Route("/save-notification-emails", name="observation_save_notification_emails", methods={"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveNotificationEmailsAction(Request $request, ValidatorInterface $validator)
    {
        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $errors = array();

        $emailConstraint = new Assert\Email();
        $emailConstraint->message = 'The value {{ value }} is not a valid email address';

        $emails = $request->get('observation_notification_emails')['notificationEmails'];

        foreach($emails as $email) {
            $errors[] = $validator->validate(
                $email,
                $emailConstraint
            );
        }

        $cleanErrors = array();

        foreach($errors as $error) {
            if(count($errors[0]) > 0) {
                $cleanErrors[] = $error[0]->getMessage();
            }
        }

        if(count($cleanErrors) > 0) {
            $response = new Response(json_encode($cleanErrors), 406);

            return $response;

        } else {
            $em = $this->getDoctrine()->getManager();

            $observation = $em->getRepository('App\Entity\Observation')->find($request->get('observation_notification_emails')['observationId']);

            $notificationEmails = ($observation->getNotificationEmails()) ? $observation->getNotificationEmails() : array();

            $observation->setNotificationEmails(array_unique(
                array_merge($notificationEmails, $emails))
            );

            $em->persist($observation);
            $em->flush();

            return new Response(json_encode($emails));
        }
    }
}