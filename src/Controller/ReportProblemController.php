<?php

namespace App\Controller;

use App\Security\Encoder\OpenSslEncoder;
use Symfony\Component\Routing\Annotation\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Route("/report-problem")
 *
 * Class ReportAProblemController
 * @package App\Controller
 */
class ReportProblemController extends AbstractController
{
    /**
     * @Route("/{_locale}/report", name="report_problem", methods={"GET", "POST"})
     * @Template
     *
     */
    public function reportAction(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createFormBuilder(array())
            ->add('report', TextareaType::class, array(
                'label' => 'What happened?',
                'attr' => array(
                    'placeholder' => 'Briefly explain what happened and indicate the steps to take to reproduce the problem',
                ),
                'constraints' => [
                    new NotBlank()
                ]
            ))
            ->add('submit', SubmitType::class, ['label' => 'Send'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = (new \Swift_Message('[BEHAVE] An user reported a problem'))
                ->setFrom('noreply@behaveproject.eu')
                ->setTo('developers@behaveproject.eu')
                ->setBody($form->getData()['report'],
                    'text/html')
            ;

            try{
                $mailer->send($message);
            }catch(\Swift_TransportException $e){
                $response = $e->getMessage() ;
            }

            $this->get('session')->getFlashbag()->add('success', 'Thank you for your contribution. We will do our best to fix the problem as soon as possible');

            return $this->redirectToRoute('homepage');
        }

        return array(
            'title' => 'Report a problem',
            'form' => $form->createView(),
        );

    }
}