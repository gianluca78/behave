<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Form\Handler\ObservationFormHandler;
use App\Form\Type\ObservationType;

use App\Entity\Observation;
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
    CONST NEW_SUCCESS_STRING = '';

    /**
     * @Route("/{id}", name="measure")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @param Observation $observation
     * @param ItemFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Template
     */
    public function createFormAction(Request $request,
                                     FormBuilder $formBuilder,
                                     Observation $observation,
                                     ItemFormHandler $formHandler)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('App\Entity\Item')->findItemsByObservation($observation);

        $formBuilder->addItems($items);
        $formBuilder->setAction($observation);

        $form = $formBuilder->getForm()->getForm();

        if ($formHandler->handle($form, $request, $this->get('translator')->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('observation_list'));
        }

        return array(
            'form' => $form->createView(),
        );

    }

}