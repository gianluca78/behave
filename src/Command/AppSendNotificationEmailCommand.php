<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class AppSendNotificationEmailCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:send-notification-email';

    protected function configure()
    {
        $this
            ->setDescription('Send a notification email a number of hours before the observation')
            ->addArgument('numberOfHours', InputArgument::REQUIRED, 'number of hours before the notification is sent')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $numberOfHours = $input->getArgument('numberOfHours');
        $mailer = $this->getContainer()->get('mailer');
        $templating = $this->getContainer()->get('twig');

        $observationDates = $this->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository('App\Entity\ObservationDate')
            ->findIncomingObservations($numberOfHours)
        ;

        foreach($observationDates as $observationDate) {

            $observation = $observationDate->getObservation();

            $observersEmails = is_array($observation->getNotificationEmails()) ? $observation->getNotificationEmails() : array();

            foreach($observersEmails as $email) {
                $message = (new \Swift_Message('[BEHAVE] Observation'))
                    ->setFrom('noreply@behaveproject.eu')
                    ->setTo($email)
                    ->setBody(
                        $templating->render(
                            'emails/observation-notification.html.twig',
                            array(
                                'student' => $observation->getStudent(),
                                'behaviour' => $observation->getName(),
                                'observationStartDate' => $observationDate->getStartDateTimestamp(),
                                'observationEndDate' => $observationDate->getEndDateTimestamp(),
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

        $io->success('Task completed with success.');
    }
}
