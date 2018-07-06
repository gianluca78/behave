<?php
namespace App\Form\Handler;

use App\Entity\ObservationDate;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\FormInterface,
    Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Observation;

class ObservationFormHandler
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

    public function handle(FormInterface $form, Request $request, $message)
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $validObject = $form->getData();

        $schedulerData = $form->get('observationScheduler')->getData();

        $this->create($validObject, $schedulerData, $message);

        return true;
    }

    public function create(Observation $entity, $schedulerData, $message)
    {
        $this->entityManager->persist($entity);

        if($entity->getHasDates()) {
            $entity->resetObservationDates();

            $firstStartDate = new \DateTime();
            $firstStartDate->setDate(
                $schedulerData->getStartDate()->format('Y'), 
                $schedulerData->getStartDate()->format('m'),
                $schedulerData->getStartDate()->format('d')
            );

            $firstEndDate = new \DateTime();
            $firstEndDate->setDate(
                $schedulerData->getStartDate()->format('Y'),
                $schedulerData->getStartDate()->format('m'),
                $schedulerData->getStartDate()->format('d')
            );

            switch($schedulerData->getTimeOption()) {
                //all day
                case 0:
                    $firstStartDate->setTime(0, 0, 0);
                    $firstEndDate->setTime(23, 59, 59);

                    break;

                //time range
                case 1:
                    $firstStartDate->setTime(
                        $schedulerData->getTimeRangeStartTime()->format('h'),
                        $schedulerData->getTimeRangeStartTime()->format('i'),
                        $schedulerData->getTimeRangeStartTime()->format('s')
                    );

                    $firstEndDate->setTime(
                        $schedulerData->getTimeRangeEndTime()->format('h'),
                        $schedulerData->getTimeRangeEndTime()->format('i'),
                        $schedulerData->getTimeRangeEndTime()->format('s')
                    );

                    break;

                //exact time
                case 2:
                    $firstStartDate->setTime(
                        $schedulerData->getExactTime()->format('h'),
                        $schedulerData->getExactTime()->format('i'),
                        $schedulerData->getExactTime()->format('s')
                    );

                    $firstEndDate->setTime(
                        $schedulerData->getExactTime()->format('h'),
                        $schedulerData->getExactTime()->format('i'),
                        $schedulerData->getExactTime()->format('s')
                    );

                    break;
            }

            $observationDate = new ObservationDate();
            $observationDate->setStartDateTimestamp($firstStartDate);
            $observationDate->setEndDateTimestamp($firstEndDate);
            $observationDate->setObservation($entity);

            $entity->addObservationDate($observationDate);

            if($schedulerData->getRepeatOption() == 1) {
                switch($schedulerData->getRepeatEndOption()) {
                    //weekly
                    case 0:
                        $weekInterval = $schedulerData->getWeeklyNumberOfWeeks();
                        $repeatEndAfterNumberOfOccurrences = $schedulerData->getRepeatEndAfterNumberOfOccurrences();
                        $weekDays = $schedulerData->getWeeklyDaysOfWeek();

                        $totalOccurrences = $repeatEndAfterNumberOfOccurrences * count($weekDays);
                        $occurrences = 0;

                        $endDate = clone $firstStartDate;
                        $endDate->modify('+ 1 years');
                        $interval = new \DateInterval('P1D');
                        $period = new \DatePeriod($firstStartDate, $interval, $endDate);

                        $fakeWeek = 0;
                        $currentWeek = $firstStartDate->format('W');

                        $scheduledDates = array();

                        foreach ($period as $date) {
                            if ($date->format('W') !== $currentWeek) {
                                $currentWeek = $date->format('W');
                                $fakeWeek++;
                            }

                            if (in_array($date->format('w'), $weekDays) && ($fakeWeek % $weekInterval === 0) ) {
                                $occurrences++;
                                $scheduledDates[] = $date;
                            }

                            if($occurrences == $totalOccurrences) {
                                break;
                            }
                        }

                        break;

                    case 1:
                        $weekInterval = $schedulerData->getWeeklyNumberOfWeeks();

                        $weekDays = $schedulerData->getWeeklyDaysOfWeek();

                        $endDate = clone $firstStartDate;
                        $endDate->modify('+ 1 years');
                        $interval = new \DateInterval('P1D');
                        $period = new \DatePeriod($firstStartDate, $interval, $endDate);

                        $fakeWeek = 0;
                        $currentWeek = $firstStartDate->format('W');

                        $scheduledDates = array();

                        foreach ($period as $date) {
                            if ($date->format('W') !== $currentWeek) {
                                $currentWeek = $date->format('W');
                                $fakeWeek++;
                            }

                            if (in_array($date->format('w'), $weekDays) && ($fakeWeek % $weekInterval === 0) ) {
                                $scheduledDates[] = $date;
                            }

                            if($date >= $schedulerData->getRepeatEndDate()) {
                                break;
                            }
                        }

                        break;
                }

                foreach($scheduledDates as $scheduledDate) {
                    $observationDate = new ObservationDate();

                    $startDate = clone $scheduledDate;
                    $startDate->setTime($firstStartDate->format('H'), $firstStartDate->format('i'));

                    $endDate = clone $scheduledDate;
                    $endDate->setTime($firstEndDate->format('H'), $firstEndDate->format('i'));

                    $observationDate->setStartDateTimestamp($startDate);
                    $observationDate->setEndDateTimestamp($endDate);
                    $observationDate->setObservation($entity);

                    $entity->addObservationDate($observationDate);
                }
            }
        }


        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', $message);
    }
}