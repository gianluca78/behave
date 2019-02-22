<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\StudentHealthInformation;
use App\Form\Handler\StudentHealthInformationFormHandler;
use App\Form\Type\StudentHealthInformationType;
use App\Security\Encoder\OpenSslEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/student-health-information")
 *
 * Class StudentHealthInformationController
 * @package App\Controller
 */
class StudentHealthInformationController extends Controller
{
    CONST NEW_SUCCESS_STRING = 'Health information inserted successfully';
    CONST EDIT_SUCCESS_STRING = 'Health information edited successfully';
    CONST DELETE_SUCCESS_STRING = 'Health information deleted successfully';

    CONST NEW_TITLE = 'Insert new health information';
    CONST EDIT_TITLE = 'Edit health information';
    CONST INDEX_TITLE = 'List of health information';

    /**
     * @Route("/delete/{id}/{ids}", name="student_health_information_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param StudentHealthInformation $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, Student $student)
    {
        $ids = json_decode($request->get('ids'), true);

        $em = $this->getDoctrine()->getManager();

        foreach($ids as $id) {
            $studentHealthInformation = $em->getRepository('App\Entity\StudentHealthInformation')->find($id);

            if($studentHealthInformation->getStudent()->getCreatorUserId() != $this->getUser()->getUserId()) {
                $response = new Response('not allowed');
                $response->setStatusCode(403);

                return $response;
            }

            $em->remove($studentHealthInformation);
            $em->flush();
        }

        $this->get('session')->getFlashbag()->add('success', $this->get('translator')->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('student_health_information_list', array('id' => $student->getId())));
    }

    /**
     * @Route("/{id}/list", name="student_health_information_list")
     * @Method({"GET"})
     * @Template
     *
     * @param Student $student
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Student $student)
    {
        if($student->getCreatorUserId() != $this->getUser()->getUserId()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $records = $this->getDoctrine()->getRepository('App\Entity\StudentHealthInformation')->findByStudent(
            $student
        );

        return array(
            'records' => $records,
            'title' => $this->get('translator')->trans(self::INDEX_TITLE),
            'student' => $student
        );
    }

    /**
     * @Route("/edit/{id}", name="student_health_information_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Observation $observation
     * @param ObservationEditFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, StudentHealthInformation $studentHealthInformation, StudentHealthInformationFormHandler $formHandler)
    {
        if($studentHealthInformation->getStudent()->getCreatorUserId() != $this->getUser()->getUserId()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $form = $this->createForm(StudentHealthInformationType::class, $studentHealthInformation, array(
            'action' => $this->generateUrl('student_health_information_edit', array('id' => $studentHealthInformation->getId()))
        ));

        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('student_health_information_list', array(
                        'id' => $studentHealthInformation->getStudent()->getId())
                )
            );
        }

        return $this->render('student_health_information/new.html.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->get('translator')->trans(self::EDIT_TITLE),
                'student' => $studentHealthInformation->getStudent()
            )
        );
    }

    /**
     * @Route("/new/{id}", name="student_health_information_new")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     * @param StudentHealthInformationFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Student $student, StudentHealthInformationFormHandler $formHandler)
    {
        if($student->getCreatorUserId() != $this->getUser()->getUserId()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $entity = new StudentHealthInformation();
        $entity->setStudent($student);

        $form = $this->createForm(StudentHealthInformationType::class, $entity, array(
            'action' => $this->generateUrl('student_health_information_new', array('id' => $student->getId()))
        ));


        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('student_health_information_list', array('id' => $student->getId())));
        }

        return array(
            'form' => $form->createView(),
            'title' => $this->get('translator')->trans(self::NEW_TITLE),
            'student' => $student
        );

    }

}