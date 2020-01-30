<?php

namespace App\Controller;

use App\CouchDb\Client;
use App\Security\Encoder\OpenSslEncoder;
use Symfony\Component\Routing\Annotation\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use Symfony\Contracts\Translation\TranslatorInterface;

use App\Form\Handler\StudentFormHandler;
use App\Form\Type\StudentType;
use App\Entity\Student;

/**
 * @Route("/student")
 *
 * Class StudentController
 * @package App\Controller
 */
class StudentController extends AbstractController
{
    CONST NEW_SUCCESS_STRING = 'Student inserted successfully';
    CONST EDIT_SUCCESS_STRING = 'Student edited successfully';
    CONST DELETE_SUCCESS_STRING = 'Student deleted successfully';
    CONST NEW_TITLE = 'Insert new student';
    CONST EDIT_TITLE = 'Edit student';
    CONST INDEX_TITLE = 'List of students';

    /**
     * @Route("/{_locale}/list", name="student_list", requirements={"locale": "en|it"}, methods={"GET"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, OpenSslEncoder $encoder, TranslatorInterface $translator)
    {
        $records = $this->getDoctrine()->getRepository('App\Entity\Student')->findByCreatorUserIdOrderedByNameAsc(
            $encoder->encrypt($this->getUser()->getUserId())
        );

        return array(
            'records' => $records,
            'title' => $translator->trans(self::INDEX_TITLE)
        );
    }

    /**
    * @Route("/{_locale}/edit/{id}", name="student_edit", requirements={"locale": "en|it"}, methods={"GET", "POST"})
    *
    * @param Request $request
    * @param Student $student
    * @param StudentFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function editAction(Request $request, Student $student, StudentFormHandler $formHandler, TranslatorInterface $translator)
    {
        $form = $this->createForm(StudentType::class, $student, array(
            'action' => $this->generateUrl('student_edit', array('id' => $student->getId())),
        ));

        if($formHandler->handle($form, $request, $translator->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('student_list'));
        }

        return $this->render('student/new.html.twig',
            array(
                'form' => $form->createView(),
                'title' => $translator->trans(self::EDIT_TITLE),
                'actionName' => $translator->trans('Edit')
            )
        );
    }

    /**
    * @Route("/{_locale}/new", name="student_new", requirements={"locale": "en|it"}, methods={"GET", "POST"})
    * @Template
    *
    * @param Request $request
    * @param StudentFormHandler $formHandler
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function newAction(Request $request, StudentFormHandler $formHandler, TranslatorInterface $translator)
    {
        $entity = new Student();
        $entity->setCreatorUserId($this->getUser()->getUserId());

        $form = $this->createForm(StudentType::class, $entity, array(
            'action' => $this->generateUrl('student_new', array(
                    'locale' => $request->getLocale()
                ))
        ));

        if($formHandler->handle($form, $request, $translator->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('student_list'));
        }

        return array(
            'form' => $form->createView(),
            'title' => $translator->trans(self::NEW_TITLE),
            'actionName' => $translator->trans('New')
        );

    }

    /**
    * @Route("/delete/{id}", name="student_delete", methods={"GET"})
    *
    * @param Request $request
    * @param Student $entity
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function deleteAction(Request $request, Student $entity, TranslatorInterface $translator, Client $couchDbClient)
    {
        foreach($entity->getObservations() as $observation) {
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
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashbag()->add('success', $translator->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('student_list'));
    }
}