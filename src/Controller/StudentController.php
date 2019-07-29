<?php

namespace App\Controller;

use App\Security\Encoder\OpenSslEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Form\Handler\StudentFormHandler;
use App\Form\Type\StudentType;
use App\Entity\Student;

/**
 * @Route("/student")
 *
 * Class StudentController
 * @package App\Controller
 */
class StudentController extends Controller
{
    CONST NEW_SUCCESS_STRING = 'Student inserted successfully';
    CONST EDIT_SUCCESS_STRING = 'Student edited successfully';
    CONST DELETE_SUCCESS_STRING = 'Student deleted successfully';
    CONST NEW_TITLE = 'Insert new student';
    CONST EDIT_TITLE = 'Edit student';
    CONST INDEX_TITLE = 'List of students';

    /**
     * @Route("/{_locale}/list", name="student_list", requirements={"locale": "en|it"})
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, OpenSslEncoder $encoder)
    {
        $records = $this->getDoctrine()->getRepository('App\Entity\Student')->findByCreatorUserIdOrderedByNameAsc(
            $encoder->encrypt($this->getUser()->getUserId())
        );

        return array(
            'records' => $records,
            'title' => $this->get('translator')->trans(self::INDEX_TITLE)
        );
    }

    /**
    * @Route("/{_locale}/edit/{id}", name="student_edit", requirements={"locale": "en|it"})
    * @Method({"GET", "POST"})  
    *
    * @param Request $request
    * @param Student $student
    * @param StudentFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function editAction(Request $request, Student $student, StudentFormHandler $formHandler)
    {
        $form = $this->createForm(StudentType::class, $student, array(
            'action' => $this->generateUrl('student_edit', array('id' => $student->getId())),
        ));

        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('student_list'));
        }

        return $this->render('student/new.html.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->get('translator')->trans(self::EDIT_TITLE),
                'actionName' => $this->get('translator')->trans('Edit')
            )
        );
    }

    /**
    * @Route("/{_locale}/new", name="student_new", requirements={"locale": "en|it"})
    * @Method({"GET", "POST"})
    * @Template
    *
    * @param Request $request
    * @param StudentFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function newAction(Request $request, StudentFormHandler $formHandler)
    {
        $entity = new Student();
        $entity->setCreatorUserId($this->getUser()->getUserId());

        $form = $this->createForm(StudentType::class, $entity, array(
            'action' => $this->generateUrl('student_new', array(
                    'locale' => $request->getLocale()
                ))
        ));

        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('student_list'));
        }

        return array(
            'form' => $form->createView(),
            'title' => $this->get('translator')->trans(self::NEW_TITLE),
            'actionName' => $this->get('translator')->trans('New')
        );

    }

    /**
    * @Route("/delete/{id}", name="student_delete")
    * @Method({"GET"})
    *
    * @param Request $request
    * @param Student $entity
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function deleteAction(Request $request, Student $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashbag()->add('success', $this->get('translator')->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('student_list'));
    }
}