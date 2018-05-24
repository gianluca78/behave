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

use App\Entity\Measure;
use App\Form\Handler\MeasureFormHandler;
use App\Form\Type\MeasureType;

use App\Form\Builder\FormBuilder;
use App\Form\Handler\ItemFormHandler;

/**
 * @Route("/measure")
 *
 * Class MeasureController
 * @package App\Controller
 */
class MeasureController extends Controller
{
    CONST NEW_COMPLETE_SUCCESS_STRING = '';
    CONST NEW_SUCCESS_STRING = 'Record inserted successfully';
    CONST EDIT_SUCCESS_STRING = 'Record edited successfully';
    CONST DATES_ADDED_SUCCESS = 'Dates added successfully';
    CONST DELETE_SUCCESS_STRING = 'Record deleted successfully';
    CONST CALENDAR_TITLE = 'Pick your favourite dates';
    CONST NEW_TITLE = 'Insert new observation';
    CONST EDIT_TITLE = 'Edit observation';
    CONST INDEX_TITLE = 'List of observations';

    /**
     * @Route("/list", name="measure_list")
     * @Method({"GET"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $records = $this->getDoctrine()->getRepository('App\Entity\Measure')->findAll();

        return array(
            'records' => $records,
            'title' => $this->get('translator')->trans(self::INDEX_TITLE)
        );
    }

    /**
     * @Route("/edit/{id}", name="measure_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Measure $observation
     * @param MeasureFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Measure $observation, MeasureFormHandler $formHandler)
    {
        $formHandler->setOriginalChoiceItems($observation->getChoiceItems());
        $formHandler->setOriginalDirectObservationItems($observation->getDirectObservationItems());
        $formHandler->setOriginalIntegerItems($observation->getIntegerItems());
        $formHandler->setOriginalMeterItems($observation->getMeterItems());
        $formHandler->setOriginalRangeItems($observation->getRangeItems());
        $formHandler->setOriginalTextItems($observation->getTextItems());

        $form = $this->createForm(MeasureType::class, $observation, array(
            'action' => $this->generateUrl('measure_edit', array('id' => $observation->getId())),
        ));

        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('measure_list'));
        }

        return $this->render('measure/new.html.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->get('translator')->trans(self::EDIT_TITLE),
                'numberOfItems' => $observation->countItems()
            )
        );
    }

    /**
     * @Route("/new", name="measure_new")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     * @param MeasureFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, MeasureFormHandler $formHandler)
    {
        $entity = new Measure();
        $entity->setCreatorUsername($this->getUser()->getUsername());

        $form = $this->createForm(MeasureType::class, $entity, array(
            'action' => $this->generateUrl('measure_new')
        ));


        if($formHandler->handle($form, $request, $this->get('translator')->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('measure_list'));
        }

        return array(
            'form' => $form->createView(),
            'title' => $this->get('translator')->trans(self::NEW_TITLE)
        );

    }

    /**
     * @Route("/delete/{id}", name="measure_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Measure $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, Measure $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashbag()->add('success', $this->get('translator')->trans(self::DELETE_SUCCESS_STRING));

        return $this->redirect($this->generateUrl('measure_list'));
    }

    /**
     * @Route("/{id}", name="measure")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @param Measure $observation
     * @param ItemFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Template
     */
    public function createFormAction(Request $request,
                                     FormBuilder $formBuilder,
                                     Measure $observation,
                                     ItemFormHandler $formHandler)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('App\Entity\Item')->findItemsByMeasure($observation);

        if(!$observation->isDateIncluded(new \DateTime())) {
            return $this->render('measure/not_allowed.html.twig');
        }

        $formBuilder->addItems($items);
        $formBuilder->setAction($observation);

        $form = $formBuilder->getForm()->getForm();

        if ($formHandler->handle($form, $request, $this->get('translator')->trans(self::NEW_COMPLETE_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('measure_list'));
        }

        return array(
            'form' => $form->createView(),
        );

    }


}