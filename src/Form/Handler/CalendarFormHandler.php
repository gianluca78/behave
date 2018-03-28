<?php
namespace App\Form\Handler;

use App\Entity\ObservationDate;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\FormInterface,
    Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Observation;

class CalendarFormHandler
{

    private $entityManager;
    private $session;

    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session
    )
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function handle(FormInterface $form, Request $request, Observation $observation, $message)
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $data = $request->get('calendar');

        $this->create($data, $observation, $message);

        return true;
    }

    public function create($data, Observation $observation, $message)
    {
        foreach($observation->getObservationDates() as $date) {
            $this->entityManager->remove($date);
            $this->entityManager->flush();
        }

        foreach($data['dates'] as $date) {
            $startDate = new \DateTime($date);
            $startDate->setTime($data['startTime']['hour'], $data['startTime']['minute'], '00');

            $endDate = new \DateTime($date);
            $endDate->setTime($data['endTime']['hour'], $data['endTime']['minute'], '00');

            $observationDate = new ObservationDate();
            $observationDate->setStartDateTimestamp($startDate);
            $observationDate->setEndDateTimestamp($endDate);
            $observationDate->setObservation($observation);

            $observation->addObservationDate($observationDate);
        }

        $this->entityManager->persist($observation);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', $message);

    }
}