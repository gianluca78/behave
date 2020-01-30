<?php

namespace App\Controller;

use App\Entity\ChoiceItem;
use App\Entity\DirectObservationItem;
use App\Entity\IntegerItem;
use App\Entity\MeterItem;
use App\Entity\RangeItem;
use App\Entity\TextItem;
use App\Form\Handler\CalendarFormHandler;
use App\Form\Type\CalendarType;
use App\Form\Type\ImportMeasureType;
use App\Utility\MeasureExporter;
use Symfony\Component\Routing\Annotation\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use Symfony\Contracts\Translation\TranslatorInterface;

use App\Entity\Measure;
use App\Entity\Observation;
use App\Form\Handler\MeasureFormHandler;
use App\Form\Type\MeasureType;

use App\Form\Builder\FormBuilder;
use App\Form\Handler\ItemFormHandler;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/measure")
 *
 * Class MeasureController
 * @package App\Controller
 */
class MeasureController extends AbstractController
{
    CONST NEW_COMPLETE_SUCCESS_STRING = 'Data saved successfully';
    CONST NEW_SUCCESS_STRING = 'Record inserted successfully';
    CONST EDIT_SUCCESS_STRING = 'Record edited successfully';
    CONST DATES_ADDED_SUCCESS = 'Dates added successfully';
    CONST DELETE_SUCCESS_STRING = 'Record deleted successfully';
    CONST CALENDAR_TITLE = 'Pick your favourite dates';
    CONST NEW_TITLE = 'Insert new measure';
    CONST EDIT_TITLE = 'Edit measure';
    CONST INDEX_TITLE = 'List of measures';

    /**
     * @Route("/{_locale}/list", name="measure_list", requirements={"locale": "en|it"}, methods={"GET"})
     * @Template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, TranslatorInterface $translator)
    {
        $records = $this->getDoctrine()->getRepository('App\Entity\Measure')->findByCreatorUserId(
            $this->getUser()->getUserId()
        );

        return array(
            'records' => $records,
            'title' => $translator->trans(self::INDEX_TITLE)
        );
    }

    /**
     * @Route("/{_locale}/edit/{id}", name="measure_edit", requirements={"locale": "en|it"}, methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Measure $observation
     * @param MeasureFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Measure $measure, MeasureFormHandler $formHandler, TranslatorInterface $translator)
    {
        $formHandler->setOriginalChoiceItems($measure->getChoiceItems());
        $formHandler->setOriginalDirectObservationItems($measure->getDirectObservationItems());
        $formHandler->setOriginalIntegerItems($measure->getIntegerItems());
        $formHandler->setOriginalMeterItems($measure->getMeterItems());
        $formHandler->setOriginalRangeItems($measure->getRangeItems());
        $formHandler->setOriginalTextItems($measure->getTextItems());

        $form = $this->createForm(MeasureType::class, $measure, array(
            'action' => $this->generateUrl('measure_edit', array('id' => $measure->getId())),
        ));

        if($formHandler->handle($form, $request, $translator->trans(self::EDIT_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('measure_list'));
        }

        return $this->render('measure/new.html.twig',
            array(
                'form' => $form->createView(),
                'title' => $translator->trans(self::EDIT_TITLE),
                'numberOfItems' => $measure->countItems(),
                'actionName' => 'Edit'
            )
        );
    }

    /**
     * @Route("/export/{id}", name="measure_export", methods={"GET"})
     *
     * @param Measure $measure
     * @param MeasureExporter $measureExporter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportMeasureAction(Measure $measure, MeasureExporter $measureExporter)
    {
        $response = new Response($measureExporter->export($measure));

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            strtolower(mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $measure->getName().'.txt'))
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    /**
     * @Route("/import", name="measure_import", methods={"GET", "POST"})
     * @Template
     *
     * @param Request $request
     * @param Measure $measure
     * @param MeasureExporter $measureExporter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importMeasureAction(Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(ImportMeasureType::class, null, array(
            'action' => $this->generateUrl('measure_import'),
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['file']->getData();

            $fileContent = unserialize(file_get_contents($file->getPathname()));

            $em = $this->getDoctrine()->getManager();

            $measure = new Measure();
            $measure->setName($fileContent['name']);
            $measure->setDescription($fileContent['description']);
            $measure->setCreatorUserId($this->getUser()->getUserId());

            foreach($fileContent['choiceItems'] as $item) {
                $item->setMeasure($measure);
                $measure->addChoiceItem($item);
            }

            foreach($fileContent['integerItems'] as $item) {
                $item->setMeasure($measure);
                $measure->addIntegerItem($item);
            }

            foreach($fileContent['meterItems'] as $item) {
                $item->setMeasure($measure);
                $measure->addMeterItem($item);
            }

            foreach($fileContent['rangeItems'] as $item) {
                $item->setMeasure($measure);
                $measure->addRangeItem($item);
            }

            foreach($fileContent['textItems'] as $item) {
                $item->setMeasure($measure);
                $measure->addTextItem($item);
            }

            foreach($fileContent['directObservationItems'] as $item) {
                $item->setMeasure($measure);
                $measure->addDirectObservationItem($item);
            }

            $em->persist($measure);
            $em->flush();

            $this->get('session')->getFlashbag()->add('success', $translator->trans('Measure imported with success!'));

            return $this->redirect($this->generateUrl('measure_list'));
        }

        return array(
            'form' => $form->createView(),
            'title' => $translator->trans('Import measure'),
        );
    }


    /**
     * @Route("/{_locale}/import-live", name="measure_import_live", methods={"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importLiveAction(Request $request) {
        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $file = $request->files->get('file');

        return new Response(
            json_encode(
                unserialize(
                    file_get_contents($file->getPathname())
                )
            )
        );
    }

        /**
     * @Route("/{_locale}/new", name="measure_new", requirements={"locale": "en|it"}, methods={"GET", "POST"})
     * @Template
     *
     * @param Request $request
     * @param MeasureFormHandler $formHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, MeasureFormHandler $formHandler, TranslatorInterface $translator)
    {
        $entity = new Measure();
        $entity->setCreatorUserId($this->getUser()->getUserId());

        $form = $this->createForm(MeasureType::class, $entity, array(
            'action' => $this->generateUrl('measure_new')
        ));


        if($formHandler->handle($form, $request, $translator->trans(self::NEW_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('measure_list'));
        }

        $numberOfItems = $form->get('choiceItems')->count() + $form->get('directObservationItems')->count() +
            $form->get('integerItems')->count() + $form->get('meterItems')->count() +
            $form->get('rangeItems')->count() + $form->get('textItems')->count();

        return array(
            'form' => $form->createView(),
            'title' => $translator->trans(self::NEW_TITLE),
            'actionName' => 'New',
            'numberOfItems' => $numberOfItems
        );

    }

    /**
     * @Route("/{_locale}/delete/{ids}", name="measure_delete", requirements={"locale": "en|it"}, methods={"GET"})
     *
     * @param Request $request
     * @param Measure $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, TranslatorInterface $translator)
    {
        $ids = json_decode($request->get('ids'), true);

        $em = $this->getDoctrine()->getManager();

        foreach($ids as $id) {
            $measure = $em->getRepository('App\Entity\Measure')->find($id);

            $observations = $em->getRepository('App\Entity\Observation')->findByMeasure($measure);

            if(!$observations) {
                $flashTypology = 'success';
                $flashMessage = $translator->trans(self::DELETE_SUCCESS_STRING);

                $em->remove($measure);
                $em->flush();
            } else {
                $flashTypology = 'warning';
                $flashMessage = sprintf($translator->trans('cant-delete-measure', array(), 'messages'), (string) $measure);
            }
        }

        $this->get('session')->getFlashbag()->add($flashTypology, $flashMessage);

        return $this->redirect($this->generateUrl('measure_list'));

    }

    /**
     * @Route("/{id}/{token}", name="measure", methods={"GET", "POST"})
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
                                     Observation $observation,
                                     ItemFormHandler $formHandler,
                                     $token,
                                     TranslatorInterface $translator)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('App\Entity\Item')->findItemsByMeasure($observation->getMeasure());

        if(!$observation->getIsEnabled() ||
            !$observation->isDateIncluded(new \DateTime()) ||
            $observation->getToken() != $token) {

            $nextDate = $em->getRepository('App\Entity\ObservationDate')->findNextObservationDate($observation);

            return $this->render('measure/not_allowed.html.twig', array(
                'nextDate' => $nextDate,
                'observation' => $observation
            ));
        }

        $formBuilder->addItems($items);
        $formBuilder->setAction($observation);
        $formBuilder->setObservationId($observation->getId());
        $formBuilder->setUserId($this->getUser()->getUserId());

        $form = $formBuilder->getForm()->getForm();

        if ($formHandler->handle($form, $request, $translator->trans(self::NEW_COMPLETE_SUCCESS_STRING))) {
            return $this->redirect($this->generateUrl('dashboard', array(
                'id' => $observation->getId(),
                'token' => $observation->getToken()
            )));
        }

        return array(
            'form' => $form->createView(),
            'title' => 'Data gathering',
            'observation' => $observation
        );

    }


}